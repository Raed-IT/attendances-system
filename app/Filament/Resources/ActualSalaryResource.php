<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActualSalaryResource\Pages;
use App\Filament\Resources\ActualSalaryResource\RelationManagers;
use App\Models\ActualSalary;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ActualSalaryResource extends Resource
{
    protected static ?string $model = ActualSalary::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $label = 'راتب     ';
    protected static ?string $pluralLabel = '  الرواتب   المستحقة ';
    protected static ?string $navigationGroup = " التقارير ";


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListActualSalaries::route('/'),
            'create' => Pages\CreateActualSalary::route('/create'),
            'edit' => Pages\EditActualSalary::route('/{record}/edit'),
        ];
    }
}
