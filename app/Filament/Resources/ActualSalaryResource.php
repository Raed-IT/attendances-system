<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActualSalaryResource\Pages;
use App\Filament\Resources\ActualSalaryResource\RelationManagers;
use App\Models\ActualSalary;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

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
                Forms\Components\Card::make()->schema([
                    TextInput::make("total")->label("الراتب المستحق ")
                        ->mask(fn(TextInput\Mask $mask) => $mask->money(prefix: '$', thousandsSeparator: ',', decimalPlaces: 1))->required(),
//                    Forms\Components\Select::make("employee_id")->relationship("employee","name")->required()->label("الموظف"),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("id")->label("ID")->sortable(),
                Tables\Columns\TextColumn::make("employee.name")->label("الموظف")->searchable(),
                Tables\Columns\BadgeColumn::make("total")->label("الراتب المستحق")->sortable(),
                Tables\Columns\BadgeColumn::make("employee.bank_no")->label("بطاقة البنك")->sortable(),


            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('month')->label("الشهر"),
                    ])
                    ->query(function (Builder $query, array $data): Builder {

                        return $query
                            ->when(
                                $data['month'],
                                function (Builder $query, $date) use ($data): Builder {

                                    return $query->whereMonth("created_at", Carbon::parse($data['month'])->month);
                                }
                            );
                    })
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
//            'create' => Pages\CreateActualSalary::route('/create'),
//            'edit' => Pages\EditActualSalary::route('/{record}/edit'),
        ];
    }
}
