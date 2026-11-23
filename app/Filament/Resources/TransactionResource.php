<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Card;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Carts & Orders';

    public static function getNavigationSort(): ?int
    {
        return 5;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([

                // General Information
                Card::make([
                    TextEntry::make('invoice')->label('Invoice'),
                    TextEntry::make('status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'pending' => 'warning',
                            'success' => 'success',
                            'expired' => 'gray',
                            'failed' => 'danger',
                        }),
                    TextEntry::make('created_at')->label('Created At'),
                ])->columns(3),

                // Customer Information
                Card::make([
                    TextEntry::make('customer.name')->label('Customer Name'),
                    TextEntry::make('customer.email')->label('Email Address'),
                    TextEntry::make('address')->label('Address'),
                ])->columns(3),

                // Ongkir Kirim
                Card::make([
                    TextEntry::make('shipping.shipping_courier')->label('Jasa Kirim'),
                    TextEntry::make('shipping.shipping_service')->label('Layanan Kirim'),
                    TextEntry::make('shipping.shipping_cost')->label('Ongkos Kirim')->numeric(decimalPlaces: 0)->money('IDR', locale: 'id'),
                ])->columns(3),

                // Transaction Details
                Card::make([
                    RepeatableEntry::make('TransactionDetails')
                        ->label('Items Details')
                        ->schema([
                            ImageEntry::make('product.image')->label('Product Image')->circular()->width(100)->height(100),
                            TextEntry::make('product.title')->label('Product Name'),
                            TextEntry::make('qty')->label('Quantity'),
                            TextEntry::make('price')->label('Price')->numeric(decimalPlaces: 0)->money('IDR', locale: 'id'),
                        ])
                        ->columns(4),
                ]),

                Card::make([
                    // Buat container grid untuk alignment
                    Grid::make(1)
                        ->schema([
                            TextEntry::make('total')
                                ->label('Grand Total')
                                ->numeric(decimalPlaces: 0)
                                ->money('IDR', locale: 'id')
                                ->size(TextEntry\TextEntrySize::Large)
                                ->color('primary')
                                ->alignEnd() // Align konten ke kanan
                        ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice')->searchable(),
                Tables\Columns\TextColumn::make('customer.name')->searchable(),
                Tables\Columns\TextColumn::make('total')
                    ->money('IDR', locale: 'id'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'success' => 'success',
                        'expired' => 'gray',
                        'failed' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at'),
            ])
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'view'  => Pages\ViewTransaction::route('/{record}'),
        ];
    }
}
