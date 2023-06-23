<?php

namespace App\Filament\Resources;

use App\Enums\AttendanceStateEnum;
use App\Enums\AttendanceTypeEnum;
use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use App\Models\Employee;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Rats\Zkteco\Lib\Helper\Util;
use Filament\Tables\Filters\Filter;
use Svg\Tag\Text;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $label = 'حركات الموظفين     ';
    protected static ?string $pluralLabel = 'حركات الموظفين ';
    protected static ?string $navigationGroup = " التقارير ";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
//                Forms\Components\Card::make()->schema([
//
//                    Forms\Components\Hidden::make("uid")->required(),
//
//                    Forms\Components\Select::make("user_id")->relationship("employee", "name")
//                        ->searchable()->required()->preload()->reactive()
//                        ->afterStateUpdated(function (callable $get, callable $set) {
//                            $employee = Employee::whereUserid($get("user_id"))->first();
//                            $set("uid", $employee->uid);
//                        }),
//
//
//                    Forms\Components\DateTimePicker::make("timestamp")->required(),
//
//                    Forms\Components\TextInput::make("state")->default(1),
//
//                    Forms\Components\Select::make("type")->options(AttendanceTypeEnum::values())->required(),
//
//                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("id")->label("ID")->searchable(),
//                Tables\Columns\TextColumn::make("uid")->label("معرف البصمة")->searchable(),
                Tables\Columns\BadgeColumn::make("employee.name")->label("الموظف")->searchable(),
                Tables\Columns\TextColumn::make("user_id")->label("معرف الموظف")->searchable(),
                Tables\Columns\TextColumn::make("timestamp")->label("تاريخ البصم")->sortable(),
                Tables\Columns\BadgeColumn::make("state")->label("الحالة")
                    ->formatStateUsing(fn($state) => AttendanceStateEnum::tryFrom(Util::getAttState($state))?->name()),
                Tables\Columns\BadgeColumn::make("type")
                    ->formatStateUsing(fn($state) => AttendanceTypeEnum::tryFrom($state)?->name())
                    ->colors(fn() => AttendanceTypeEnum::colors())
                    ->label("نوع"),
            ])
            ->filters([
                Filter::make('timestamp')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                       ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('timestamp', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('timestamp', '<=', $date),
                            ) ;
                    })
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAttendances::route('/'),
//            'create' => Pages\CreateAttendance::route('/create'),
//            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
