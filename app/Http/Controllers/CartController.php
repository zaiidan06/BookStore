<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Delivery;
use App\Models\Transaction;
use Auth;
use DB;
use Illuminate\Http\Request;
use Str;

class CartController extends Controller
{
    public function index()
    {
        // Ambil cart berdasarkan user yang sedang login
        $cartItems = CartItem::with('book')->where('user_id', auth()->id())->get();
        return view('cart', compact('cartItems'));
    }


    public function addToCart(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Jika user sudah punya item yang sama di cart, update quantity
        $existingItem = CartItem::where('user_id', auth()->id())
            ->where('book_id', $request->book_id)
            ->first();

        if ($existingItem) {
            $existingItem->quantity += $request->quantity;
            $existingItem->save();
        } else {
            CartItem::create([
                'user_id' => auth()->id(),
                'book_id' => $request->book_id,
                'quantity' => $request->quantity,
            ]);
        }

        return back()->with('success', 'Buku berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartItem::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $item->quantity = $request->quantity;
        $item->save();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Item berhasil diperbarui.',
                'new_total' => $item->quantity * $item->book->book_price,
            ]);
        }

        return back()->with('success', 'Jumlah berhasil diperbarui.');
    }


    public function remove($id)
    {
        $item = CartItem::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $item->delete();

        return redirect()->route('cart.index')->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function showCheckout(Request $request)
    {
        $selectedItems = CartItem::whereIn('id', $request->input('selected_items', []))->get();
        return view('transaction', compact('selectedItems', 'courier'));
    }

    // CartController.php
    public function checkout(Request $request)
    {
        $couriers = Delivery::all();
        $transactions = Transaction::all();

        $paymentTypes = [
            'cash' => 'Cash',
            'bank' => 'Transfer Bank',
            'ovo' => 'Ovo',
        ];

        // Ambil ID item yang dipilih user
        $selectedItemIds = $request->input('selected_items', []);

        // Ambil user ID yang sedang login
        $userId = Auth::id();

        // Ambil item yang dipilih, milik user tersebut, dan include relasi book
        $cartItems = CartItem::whereIn('id', $selectedItemIds)
            ->where('user_id', $userId)
            ->with('book')
            ->get();

        // $shippingCosts = [
        //     'standard' => 10000,
        //     'express' => 20000,
        //     'same_day' => 30000,
        // ];

        // $shippingCost = $shippingCosts['shipping_option'] ?? 10000;

        // Hitung total harga
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->book->book_price * $item->quantity;
        });

        $shippingCost = $request->input('shipping_cost', 0);
        $totalPayment = $totalPrice + $shippingCost;

        // Kirim ke view
        return view('transaction', compact('cartItems', 'totalPrice', 'transactions', 'couriers', 'paymentTypes', 'totalPayment'))->with('user', auth()->user());
    }

    public function storeTransaction(Request $request)
{
    $validated = $request->validate([
        'payment_type' => 'required|in:cash,bank,ovo',
        'item_ids' => 'required|array',
        'phone_number' => 'required|string',
        'shipping_address' => 'required|string',
        'delivery_courier' => 'required|in:JNE,J&T,SiCepat,AnterAja',
        'shipping_option' => 'required|in:standard,express,same_day',
        'shipping_cost' => 'required|numeric|min:0',
    ]);

    DB::beginTransaction();

    try {

        // 1. Buat delivery record pertama kali
        $delivery = Delivery::create([
            'phone_number' => $validated['phone_number'],
            'shipping_address' => $validated['shipping_address'],
            'delivery_courier' => $validated['delivery_courier'],
            'receipt_code' => fake(locale: 'id_ID')->uuid,
            'status_delivery' => 'processing',
            'shipping_cost' => $validated['shipping_cost'],
        ]);

        // 2. Proses semua item sekaligus
        $cartItems = CartItem::with('book')
            ->where('user_id', auth()->id())
            ->whereIn('id', $validated['item_ids'])
            ->get();

        if($cartItems->isEmpty()) {
            throw new \Exception('Keranjang belanja kosong');
        }

        // 3. Validasi stok sekaligus
        foreach($cartItems as $item) {
            if($item->book->book_stock < $item->quantity) {
                throw new \Exception("Stok {$item->book->book_name} tidak mencukupi");
            }
        }

        // 4. Kurangi saldo user
        $itemTotal = $cartItems->sum(fn($item) =>
            $item->book->book_price * $item->quantity
        );

         // Menghitung total harga (produk + pengiriman)
         $totalPayment = $itemTotal + $validated['shipping_cost'];

        if(auth()->user()->balance < $totalPayment) {
            throw new \Exception('Saldo tidak mencukupi');
        }

        // 5. Create transactions
        $transactions = [];
        foreach($cartItems as $item) {
            $transactions[] = [
                'user_id' => auth()->id(),
                'book_id' => $item->book_id,
                'delivery_id' => $delivery->id,
                'quantity' => $item->quantity,
                'total_price' => $item->book->book_price * $item->quantity,
                'total_payment' => $totalPayment,
                'payment_type' => $validated['payment_type'],
                'payment_status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ];

            $item->book->decrement('book_stock', $item->quantity);
        }

        Transaction::insert($transactions); // Bulk insert

        // 6. Hapus cart items
        CartItem::whereIn('id', $validated['item_ids'])->delete();


        // // 7. Kurangi saldo user
        // auth()->user()->balance -= $totalPayment;
        // auth()->user()->save();
        DB::commit();

        session()->flash('pending', 'Pembelian sedang dalam proses, menunggu konfirmasi admin.');
        return redirect()->route('transaction.paymentHistory');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', $e->getMessage());
    }
}

}
