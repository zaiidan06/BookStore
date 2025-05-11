<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Delivery;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Picqer\Barcode\BarcodeGenerator;

class TransactionController extends Controller
{
    public function paymentHistory()
    {
        $user = auth()->user();

        $lastTransactions = Transaction::with('book')
        ->where('user_id', $user->id)
        ->latest()
        ->take(5)
        ->get();
        return view('paymentHistory',compact('user', 'lastTransactions'));
    }


    public function updateStatus(Delivery $delivery)
    {
        DB::transaction(function() use ($delivery) {
            $delivery->transactions()->update(['payment_status' => 'paid']);

            $user = $delivery->transactions->first()->user;
            $totalPayment = $delivery->transactions->sum('total_payment');
            $user->balance -= $totalPayment;
            $user->save();

            $cartItemIds = $delivery->transactions->pluck('cart_item_id');
            CartItem::whereIn('id', $cartItemIds)->delete();
        });


        session()->flash('paid', 'Pembelian berhasil!');
        return back()->with('success', 'Transaksi berhasil dikonfirmasi!');
    }


    public function paymentDetails($id)
    {
        $user = auth()->user();

        $transaction = Transaction::with('book')
            ->where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('paymentDetails', compact('user', 'transaction'));
    }

    public function generatePDF()
    {
        $user = auth()->user();

        $lastTransactions = Transaction::with(['book', 'delivery'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $totalBayar = $lastTransactions->sum('total_payment');

        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode('1234567890', BarcodeGenerator::TYPE_CODE_128);

        $barcodePath = storage_path('app/public/barcode.png');
        file_put_contents($barcodePath, $barcode);

        $pdf = Pdf::loadView('template.invoice', [
            'user' => $user,
            'lastTransactions' => $lastTransactions,
            'totalBayar' => $totalBayar,
            'barcodePath' => $barcodePath,
            'isPdf' => true
        ]);

        return $pdf->download('invoice-semua-transaksi-' . now()->format('Ymd-His') . '.pdf');
    }

    public function generateSinglePDF($id)
    {
        $user = auth()->user();

        $transaction = Transaction::with(['book', 'delivery'])
            ->where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        $lastTransactions = collect([$transaction]);
        $totalBayar = $transaction->total_payment;

        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode('1234567890', BarcodeGenerator::TYPE_CODE_128);

        $barcodePath = storage_path('app/public/barcode.png');
        file_put_contents($barcodePath, $barcode);

        $pdf = Pdf::loadView('template.invoice', [
            'user' => $user,
            'lastTransactions' => $lastTransactions,
            'totalBayar' => $totalBayar,
            'barcodePath' => $barcodePath,
            'isPdf' => true
        ]);

        return $pdf->download('invoice-transaksi-' . $id . '-' . now()->format('Ymd-His') . '.pdf');
    }
}
