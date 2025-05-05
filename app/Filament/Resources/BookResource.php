<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Models\Book;
use App\Models\BookCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Books';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Books')
                ->description('Create book input form')
                ->schema([
                    Forms\Components\FileUpload::make('book_image')
                        ->image()
                        ->nullable()
                        ->columnSpanFull(),

                    Forms\Components\Select::make('book_categories_id')
                        ->label('Book Category')
                        ->options(BookCategory::pluck('name', 'id')->toArray())
                        ->searchable()
                        ->required(),

                    Forms\Components\TextInput::make('book_name')
                        ->label('Book Name')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Textarea::make('book_description')
                        ->label('Description')
                        ->required()
                        ->maxLength(500),

                    Forms\Components\TextInput::make('book_stock')
                        ->label('Stock')
                        ->numeric()
                        ->default(0)
                        ->minValue(0),

                    Forms\Components\TextInput::make('book_price')
                        ->label('Price')
                        ->prefix('Rp'),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('book_name')
                    ->label('Book Name')
                    ->searchable(),

                Tables\Columns\ImageColumn::make('book_image')
                    ->label('Image'),

                Tables\Columns\TextColumn::make('book_description')
                    ->label('Description')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('book_stock')
                    ->label('Stock'),

                Tables\Columns\TextColumn::make('book_price')
                    ->label('Price')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 2, ',', '.')),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
