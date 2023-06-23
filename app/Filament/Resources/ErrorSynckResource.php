<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ErrorSynckResource\Pages;
use App\Filament\Resources\ErrorSynckResource\RelationManagers;
use App\Models\ErrorSynck;
use App\Models\ErrorSyncModel;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ErrorSynckResource extends Resource
{
    protected static ?string $model = ErrorSyncModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $label="الاخطاء في عمليات المزامنة ";
    protected static ?string $navigationGroup=" الاعدادات ";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              Forms\Components\Card::make()->schema([
                  Forms\Components\Textarea::make("content")->label("الخطاء"),
              ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make("id")->label("رقم الخطاء"),
                Tables\Columns\TextColumn::make("content")->label("الخطاء"),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageErrorSyncks::route('/'),
        ];
    }
}
