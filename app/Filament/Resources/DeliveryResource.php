<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeliveryResource\Pages;
use App\Filament\Resources\DeliveryResource\RelationManagers;
use App\Models\Delivery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeliveryResource extends Resource
{
    protected static ?string $model = Delivery::class;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';
    protected static ?string $navigationGroup = 'Transactions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Delivery')
                    ->relationship('delivery')
                    ->schema([
                        Forms\Components\TextInput::make('phone_number')->required(),
                        Forms\Components\TextInput::make('kurir')->required(),
                        Forms\Components\Textarea::make('shipping_address')->required(),
                        Forms\Components\TextInput::make('nomor_resi'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'delivered' => 'Delivered',
                            ])
                            ->default('pending')
                            ->required()
                            ->native(false),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Phone Number'),
                Tables\Columns\TextColumn::make('shipping_address')
                    ->label('Address'),
                Tables\Columns\TextColumn::make('delivery_courier')
                    ->label('delivery_courier'),
                Tables\Columns\TextColumn::make('receipt_code')
                    ->label('Code'),
                Tables\Columns\BadgeColumn::make('status_delivery')
                    ->colors([
                        'warning' => 'processing',
                        'success' => 'delivered',
                    ])
                    ->label('Status Delivery'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeliveries::route('/'),
            'create' => Pages\CreateDelivery::route('/create'),
            'edit' => Pages\EditDelivery::route('/{record}/edit'),
        ];
    }
}
