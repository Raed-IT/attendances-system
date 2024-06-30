<?php

namespace App\Filament\Resources;

use App\Enums\AttendanceStateEnum;
use App\Enums\AttendanceTypeEnum;
use App\Filament\Resources\AttendanceMonthResource\Pages;
use App\Filament\Resources\AttendanceMonthResource\RelationManagers;
use App\Models\AttendanceMonth;
use App\Models\Employee;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Rats\Zkteco\Lib\Helper\Util;
use Livewire\Component as Livewire;

class AttendanceMonthResource extends Resource
{

    protected static ?string $model = AttendanceMonth::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $label = 'حركة موظف  ';
    protected static ?string $pluralLabel = '  حركات الموظفين لهذا الشهر ';

    protected static ?string $navigationGroup = " التقارير الشهرية";

    protected static function getNavigationBadge(): ?string
    {
        return AttendanceMonth::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\Select::make("user_id")
                        ->relationship("employee", "name")
                        ->searchable()->required()->reactive()->preload()
                        ->afterStateUpdated(function (callable $get, callable $set) {
                            $employee = Employee::whereUserid($get('user_id'))->first();
                            $set("uid", $employee->uid);
                        }),
                    Forms\Components\Hidden::make("uid")->reactive(),
                    Forms\Components\DateTimePicker::make("timestamp")->required(),
                    Forms\Components\Hidden::make("state")->default(1),
                    Forms\Components\Select::make("type")->options(AttendanceTypeEnum::values())->required(),
                    Forms\Components\Select::make("device_id")->relationship("device", "name")->label("البصامة")->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("id")->label("ID")->searchable(),
                Tables\Columns\BadgeColumn::make("employee.name")->label("الموظف")->searchable(),
                Tables\Columns\TextColumn::make("user_id")->label("معرف الموظف")->searchable(),
                Tables\Columns\TextColumn::make("timestamp")->getStateUsing(fn($record) => \Illuminate\Support\Carbon::parse($record->timestamp)->format('Y-m-d'))->label("تاريخ البصم")->sortable(),
                Tables\Columns\TextColumn::make("created_at")->getStateUsing(fn($record) => Carbon::parse($record->timestamp)->format('H:i:s'))->label("توقيت البصم")->sortable(),                Tables\Columns\BadgeColumn::make("state")->label("الحالة")
                    ->formatStateUsing(fn($state) => AttendanceStateEnum::tryFrom(Util::getAttState($state))?->name()),
                Tables\Columns\BadgeColumn::make("type")
                    ->formatStateUsing(fn($state) => AttendanceTypeEnum::tryFrom($state)?->name())
                    ->colors(fn() => AttendanceTypeEnum::colors())
                    ->label("نوع"),
            ])
            ->filters([
                SelectFilter::make("user_id")
                    ->options(Employee::all()->pluck("name", "userid"))->searchable()->label("الموظف "),

                SelectFilter::make("type")->options(collect(AttendanceTypeEnum::cases())->pluck("name", "value")),

                Filter::make('timestamp')
                    ->form([
                        Forms\Components\DateTimePicker::make('from'),
                        Forms\Components\DateTimePicker::make('to'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn($q) => $q->when($data["to"], fn($qu) => $qu->whereBetween('timestamp', [Carbon::parse($data['from']) , Carbon::parse($data['to']) ])
                                )
                            );
                    })
            ])
            ->actions([

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListAttendanceMonths::route('/'),
//            'create' => Pages\CreateAttendanceMonth::route('/create'),
//            'edit' => Pages\EditAttendanceMonth::route('/{record}/edit'),
        ];
    }
}
