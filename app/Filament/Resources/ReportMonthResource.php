<?php

namespace App\Filament\Resources;

use App\Enums\PermanenceTypeEnum;
use App\Filament\Resources\ReportMonthResource\Pages;
use App\Filament\Resources\ReportMonthResource\RelationManagers;
use App\Models\ReportMonth;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReportMonthResource extends Resource
{
    protected static ?string $model = ReportMonth::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $label = 'تقرير   ';
    protected static ?string $pluralLabel = ' تقارير ساعات الدوام لهذا الشهر';

    protected static ?string $navigationGroup = " التقارير الشهرية";

    protected static function getNavigationBadge(): ?string
    {
        return ReportMonth::count();
    }

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
                Tables\Columns\TextColumn::make("id")->sortable()->label("ID"),
                Tables\Columns\BadgeColumn::make("employee.name")->label("الموظف")->searchable(),
                Tables\Columns\BadgeColumn::make("total_time")->label("الحقيقيةالساعات ")->searchable(),
                Tables\Columns\TextColumn::make("hours")->label("ساعات الطبيعية "),
                Tables\Columns\TextColumn::make("fault_time")->label("ساعات الغياب "),
                Tables\Columns\TextColumn::make("over_time")->label("ساعات الإضافي "),
                Tables\Columns\TextColumn::make("over_time")->label("ساعات العمل الاضافي"),
                Tables\Columns\BadgeColumn::make("permanence_type")->label("نوع الراتب")->getStateUsing(function ($record) {
                    return PermanenceTypeEnum::tryFrom($record->permanence_type)?->name();
                })->color(fn($record)=>PermanenceTypeEnum::tryFrom($record->permanence_type)?->color()),
                Tables\Columns\TextColumn::make("moth")->label("تقرير شهر ")->sortable(),

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
            'index' => Pages\ListReportMonths::route('/'),
//            'create' => Pages\CreateReportMonth::route('/create'),
//            'edit' => Pages\EditReportMonth::route('/{record}/edit'),
        ];
    }
}
