<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RivalResource\Pages;
 use App\Models\Employee;
use App\Models\Reward;
use App\Models\Rival;
 use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RivalResource extends Resource
{


    protected static ?string $model = Rival::class;

    protected static ?string $navigationIcon = 'heroicon-o-trending-down';
     protected static ?string $label = 'خصم ';
    protected static ?string $pluralLabel = 'الخصومات';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\Select::make("employee_id")->options(function () {
                        return Employee::all()->pluck("name", "id");
                    })->searchable()->label("الموضف ")->required(),

                    TextInput::make('val')->mask(fn(TextInput\Mask $mask) => $mask->money(prefix: '$',))
                        ->label("قيمة الخصم")->required(),
                    Forms\Components\Textarea::make("description")->label("سبب الخصم ")->nullable(),

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("id")->label("ID")->sortable(),
                Tables\Columns\BadgeColumn::make('employee.name')->label("الموظف")->color("secondary"),
                Tables\Columns\TextColumn::make('val')->label("قيمة الخصم"),
                Tables\Columns\TextColumn::make('description')->label("سبب الخصم"),
                Tables\Columns\BadgeColumn::make('created_at')
                    ->getStateUsing(fn(Rival $record) => is_null($record->date) ? $record->created_at->format("d-m-Y") : $record->date)
                    ->label("تاريخ")->sortable(),
            ])
            ->filters([
//                ...static::getFilter(showSection: false)

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
            'index' => Pages\ManageRivals::route('/'),
        ];
    }
}
