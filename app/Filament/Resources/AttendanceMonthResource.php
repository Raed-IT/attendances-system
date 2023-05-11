<?php

namespace App\Filament\Resources;

use App\Enums\AttendanceStateEnum;
use App\Enums\AttendanceTypeEnum;
use App\Filament\Resources\AttendanceMonthResource\Pages;
use App\Filament\Resources\AttendanceMonthResource\RelationManagers;
use App\Models\AttendanceMonth;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Rats\Zkteco\Lib\Helper\Util;

class AttendanceMonthResource extends Resource
{
    protected static ?string $model = AttendanceMonth::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $label = 'تقرير  الشهري  ';
    protected static ?string $pluralLabel = ' تقارير  هذا الشهر';

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
                Tables\Columns\TextColumn::make("uid")->label("معرف البصمة")->searchable(),
                Tables\Columns\BadgeColumn::make("employee.name")->label("الموظف")->searchable(),
                Tables\Columns\TextColumn::make("user_id")->label("معرف الموظف")->searchable(),
                Tables\Columns\TextColumn::make("timestamp")->label("تاريخ البصم")->sortable(),
                Tables\Columns\BadgeColumn::make("state")->label("الحالة")
                    ->formatStateUsing(fn($state) => AttendanceStateEnum::tryFrom(Util::getAttState($state))->name()),
                Tables\Columns\BadgeColumn::make("type")
                    ->formatStateUsing(fn($state) => AttendanceTypeEnum::tryFrom(Util::getAttType($state))->name())
                    ->colors([
                        AttendanceTypeEnum::CHECK_OUT->color() => AttendanceTypeEnum::CHECK_OUT->value,
                        AttendanceTypeEnum::CHECK_IN->color() => AttendanceTypeEnum::CHECK_IN->value,
                    ])
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
                                fn (Builder $query, $date): Builder => $query->whereDate('timestamp', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('timestamp', '<=', $date),
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
            'index' => Pages\ListAttendanceMonths::route('/'),
            'create' => Pages\CreateAttendanceMonth::route('/create'),
            'edit' => Pages\EditAttendanceMonth::route('/{record}/edit'),
        ];
    }
}
