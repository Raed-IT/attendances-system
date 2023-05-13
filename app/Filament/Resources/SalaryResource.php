<?php

namespace App\Filament\Resources;

use App\Enums\PermanenceTypeEnum;
use App\Filament\Resources\SalaryResource\Pages;
use App\Filament\Resources\SalaryResource\RelationManagers;
use App\Models\Salary;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalaryResource extends Resource
{
    protected static ?string $model = Salary::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $label = 'راتب';
    protected static ?string $pluralLabel = 'انواع الرواتب   ';
    protected static ?string $navigationGroup = " الرواتب";
    protected static function getNavigationBadge(): ?string
    {
        return Salary::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([

                    TextInput::make('price')->
                    mask(fn(TextInput\Mask $mask) => $mask->money(prefix: '$'))
                        ->required()->label("اجور الموظف ")
                        ->helperText('اجور الموظف بحسب الدوام المجدد '),

                    Forms\Components\Select::make("type")->options(PermanenceTypeEnum::values()->all())->required()->label("نوع الدوام")->reactive(),


                    TextInput::make("count_of_shift")->label("عدد الساعات لكل اجر ")->hidden(function (callable $get) {
                        if (!$get('count_of_shift')) {
                            return PermanenceTypeEnum::CONSTANT->value == $get("type");
                        }
                        return false;
                    })

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("id")->label("ID")->sortable(),
                Tables\Columns\TextColumn::make("price")->formatStateUsing(fn($state) => $state . " $ ")->label("اجور ")->sortable(),
                Tables\Columns\BadgeColumn::make("type")
                    ->formatStateUsing(fn($state) => PermanenceTypeEnum::tryFrom($state)->name())
                    ->colors(PermanenceTypeEnum::colors()->all())
                    ->label("نوع الدوام ")->sortable()->searchable(),
                Tables\Columns\TextColumn::make("count_of_shift")->formatStateUsing(fn($state) => $state == null ? '' : $state . " ساعة ")->label("ساعة لاستحقاق اجر ")->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSalaries::route('/'),
        ];
    }
}
