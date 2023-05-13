<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RewardResource\Pages;
 use App\Models\Employee;
use App\Models\Reward;
 use Carbon\Carbon;
use Faker\Provider\Text;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RewardResource extends Resource
{


    protected static ?string $model = Reward::class;

    protected static ?string $navigationIcon = 'heroicon-o-trending-up';

    protected static ?string $label = 'مكافئة ';
    protected static ?string $pluralLabel = 'المكافئات';




    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\Select::make("employee_id")->options(function () {
                        return Employee::all()->pluck("name", "id");
                    })->searchable()->label("الموضف ")->required(),

                    TextInput::make('val')->mask(fn(TextInput\Mask $mask) => $mask->money(prefix: '$',))
                        ->label("قيمة الكافئة")->required(),
                    Forms\Components\Textarea::make("description")->label("سبب المكافئة ")->nullable(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("id")->label("ID")->sortable(),
                Tables\Columns\BadgeColumn::make('employee.name')->label("الموظف")->color("secondary"),
                Tables\Columns\TextColumn::make('val')->label("قيمة المكافئة")->sortable(),
                Tables\Columns\TextColumn::make('description')->label("سبب المكافئة"),
                Tables\Columns\BadgeColumn::make('created_at')
                    ->getStateUsing(fn(Reward $record) => is_null($record->date) ? $record->created_at->format("d-m-Y") : $record->date)
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
            'index' => Pages\ManageRewards::route('/'),
        ];
    }
}
