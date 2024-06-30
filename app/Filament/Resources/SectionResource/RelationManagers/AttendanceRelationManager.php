<?php

namespace App\Filament\Resources\SectionResource\RelationManagers;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Enums\AttendanceStateEnum;
use App\Enums\AttendanceTypeEnum;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component as Livewire;
use PHPUnit\Util\Filter;
use Rats\Zkteco\Lib\Helper\Util;

class AttendanceRelationManager extends RelationManager
{
    protected static string $relationship = 'attendance';

    protected static ?string $recordTitleAttribute = 'employee.name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("id")->label("ID")->searchable(),
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
                \Filament\Tables\Filters\Filter::make('employee_id')
                    ->form([
                        Forms\Components\Select::make('employee')->options(function (Livewire $livewire) {
                            return Employee::whereSectionId($livewire->ownerRecord->id)->pluck('name', 'id')->toArray();
                        })->label('موظفين هذا القسم'),
                        Forms\Components\Checkbox::make('monthly')->default(true)->label("بصمات هذا الشهر فقط"),
                    ])->
                    query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['monthly'], fn($q) => $q->whereMonth('attendances.created_at', Carbon::now()->month))
                            ->when($data['employee'], fn($q) => $q->whereHas('employee', fn($qu) => $qu->whereId($data['employee'])));
                    })
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export')
//                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
