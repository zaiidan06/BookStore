<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers\DeliveryRelationManager;
use App\Models\Book;
use App\Models\CartItem;
use App\Models\Transaction;
use DB;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Transactions';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('User')
                ->description('Biodata buyer')
                ->schema([
                    Forms\Components\Select::make('user_id')
                        ->relationship('user', 'name')
                        ->preload()
                        ->native(false)
                        ->label('Username')
                        ->required(),
                        Forms\Components\Select::make('phone_number')
                        ->relationship('user', 'phone_number')
                        ->preload()
                        ->native(false)
                        ->label('Phone Number')
                        ->required(),
                        Forms\Components\Select::make('shipping_address')
                        ->relationship('user', 'shipping_address')
                        ->preload()
                        ->native(false)
                        ->label('Address')
                        ->required(),
                ])->columnSpanFull(),

            Forms\Components\Section::make('Books')
                ->description('Description book')
                ->schema([
                    Forms\Components\Select::make('book_id')
                        ->options(Book::pluck('book_name', 'id')->toArray())
                        ->preload()
                        ->native(false)
                        ->label('Book Name')
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                            self::updateBookFields($state, $set, $get);
                            $set('total_payment', $get('total_price'));
                        }),

                    Forms\Components\FileUpload::make('book.book_image')
                        ->image()
                        ->disabled()
                        ->label('Book Image')
                        ->dehydrated(),

                        Forms\Components\TextInput::make('book.book_price')
                        ->prefix('Rp')
                        ->disabled()
                        ->label('Book Price')
                        ->reactive()
                        ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.')),

                    Forms\Components\TextInput::make('quantity')
                        ->numeric()
                        ->default(1)
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                            $price = (float) str_replace(['.', ','], ['', '.'], $get('book_price'));
                            $totalPrice = $price * $state;
                            $set('total_price', $totalPrice);
                            $set('total_payment', $totalPrice);
                        }),

                    Forms\Components\TextInput::make('total_price')
                        ->prefix('Rp')
                        ->disabled()
                        ->reactive()
                        ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.')),
                ])->columns(2),

            Forms\Components\Section::make('Payment')
                ->description('Description of buyer payment')
                ->schema([
                    Forms\Components\TextInput::make('total_payment')
                        ->prefix('Rp')
                        ->required()
                        ->reactive()
                        ->default(function (Forms\Get $get) {
                            return $get('total_price');
                        })
                        ->formatStateUsing(function ($state) {
                            return number_format((float) $state, 2, ',', '.');
                        })
                        ->dehydrateStateUsing(function ($state) {
                            return (float) str_replace(['.', ','], ['', '.'], $state);
                        }),

                    Forms\Components\Select::make('payment_type')
                        ->native(false)
                        ->options([
                            'cash' => 'Cash',
                            'bank' => 'Bank Transfer',
                            'ovo' => 'OVO',
                        ])
                        ->default('cash'),

                    Forms\Components\Select::make('payment_status')
                        ->native(false)
                        ->options([
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                            'cancel' => 'Cancel',
                        ])
                        ->default('pending'),
                ])->columns(columns: 3),

            Forms\Components\Section::make('Delivery')
                ->relationship('delivery')
                ->schema([
                    Forms\Components\TextInput::make('phone_number')
                    ->required(),
                    Forms\Components\TextInput::make('delivery_courier')
                    ->required(),
                    Forms\Components\Textarea::make('shipping_address')
                    ->required(),
                    Forms\Components\Select::make('shipping_option')
                    ->options([
                        'standard' => 'Standard',
                        'express' => 'Express',
                        'same_day' => 'Same Day',
                    ])
                    ->required(),
                    Forms\Components\TextInput::make('receipt_code'),
                    Forms\Components\Select::make('status_delivery')
                        ->options([
                            'processing' => 'Processing',
                            'delivered' => 'Delivered',
                        ])
                        ->default('processing')
                        ->required()
                        ->native(false),
                ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable(),

                Tables\Columns\ImageColumn::make('book.book_image')
                    ->label('Book Image'),

                Tables\Columns\TextColumn::make('book.book_name')
                    ->label('Book Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('quantity'),

                Tables\Columns\TextColumn::make('total_price')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 2, ',', '.')),

                Tables\Columns\TextColumn::make('total_payment')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 2, ',', '.')),

                Tables\Columns\TextColumn::make('payment_type'),

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->colors([
                        'danger' => 'cancel',
                        'warning' => 'pending',
                        'success' => 'paid',
                    ]),
                Tables\Columns\TextColumn::make('delivery.delivery_courier')
                    ->label('Delivery Courier'),
                Tables\Columns\TextColumn::make('delivery.shipping_option')
                    ->label('Shipping Option'),
                Tables\Columns\TextColumn::make('delivery.shipping_address')
                    ->label('Address'),
                Tables\Columns\TextColumn::make('delivery.phone_number')
                    ->label('Phone Number'),
                Tables\Columns\TextColumn::make('delivery.receipt_code')
                    ->label('Code'),
                Tables\Columns\BadgeColumn::make('delivery.status_delivery')
                    ->colors([
                        'warning' => 'processing',
                        'success' => 'delivered',
                    ])
                    ->label('Status Delivery'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('approve_payment')
                ->label('Approve Payment')
                ->icon('heroicon-s-check')
                ->color('success')
                ->visible(fn (Transaction $record): bool => $record->payment_status !== 'paid' && $record->payment_status !== null)
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Pembayaran')
                ->modalDescription('Apakah Anda yakin ingin menyetujui pembayaran ini?')
                ->action(function (Transaction $record) {
                    DB::transaction(function () use ($record) {
                        $record->update([
                            'payment_status' => 'paid',
                        ]);

                        if ($record->delivery) {
                            $record->delivery->update([
                                'status_delivery' => 'delivered',
                            ]);
                        }

                        $record->user->decrement('balance', $record->total_payment);

                        if ($record->cart_item_id) {
                            $cartItem = CartItem::find($record->cart_item_id);
                            if ($cartItem) {
                                $cartItem->delete();
                            }
                        }
                    });

                    Notification::make()
                    ->title('Pembayaran Disetujui')
                    ->body('Pembayaran transaksi buku ' . $record->book->book_name . ' Oleh ' . $record->user->name . ' dengan ID #' .$record->id . ' Berhasil!')
                    ->success()
                    ->send();
                })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DeliveryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    private static function updateBookFields($state, Forms\Set $set, Forms\Get $get)
    {
        if (!$state)
            return;

        $book = Book::find($state);
        $quantity = $get('quantity') ?? 1;

        if ($book) {
            $set('book_price', $book->book_price);
            $set('book_image', $book->book_image ? [$book->book_image] : []);
            $set('total_price', $book->book_price * $quantity);
        } else {
            $set('book_price', 0);
            $set('total_price', 0);
            $set('book_image', []);
        }
    }
}
