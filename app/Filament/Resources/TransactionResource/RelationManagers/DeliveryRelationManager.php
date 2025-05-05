<?php

namespace App\Filament\Resources\TransactionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeliveryRelationManager extends RelationManager
{
    protected static string $relationship = 'delivery';
    protected static ?string $title = 'Delivery Info';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('phone_number')
                    ->required()
                    ->label('Phone Number'),

                Forms\Components\Textarea::make('shipping_address')
                    ->required()
                    ->label('Shipping Address'),

                Forms\Components\TextInput::make('delivery_courier')
                    ->required(),

                Forms\Components\TextInput::make('receipt_code')
                    ->label('Tracking Number'),

                Forms\Components\Select::make('status_delivery')
                    ->options([
                        'processing' => 'Processing',
                        'delivered' => 'Delivered',
                    ])
                    ->default('processing')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Delivery')
            ->columns([
                Tables\Columns\TextColumn::make('phone_number')->label('Phone'),
                Tables\Columns\TextColumn::make('shipping_address')->limit(30),
                Tables\Columns\TextColumn::make('delivery_courier'),
                Tables\Columns\TextColumn::make('receipt_code'),
                Tables\Columns\BadgeColumn::make('status_delivery')->colors([
                    'warning' => 'processing',
                    'success' => 'delivered',
                ]),            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
