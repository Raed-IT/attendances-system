<?php

namespace App\Filament\Resources\DeviceResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeesRelationManager extends RelationManager
{
    protected static string $relationship = 'employees';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name")->label("اسم الموضف ")->searchable(),
                Tables\Columns\TextColumn::make("uid")->label("uid")->searchable(),
                Tables\Columns\TextColumn::make("userid")->label("userid")->sortable(),
                Tables\Columns\TextColumn::make("namerole")->label("namerole"),
                Tables\Columns\TextColumn::make("role")->label("role"),
                Tables\Columns\TextColumn::make("password")->label("password"),
                Tables\Columns\TextColumn::make("cardno")->label("cardno")->searchable(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
             ])
            ->actions([


            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
