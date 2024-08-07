<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeviceResource\RelationManagers\EmployeesRelationManager;
use App\Filament\Resources\SectionResource\Pages;
use App\Filament\Resources\SectionResource\RelationManagers\AttendanceRelationManager;
use App\Models\Section;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;


class SectionResource extends Resource
{
    protected static ?string $model = Section::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $label = 'قسم';
    protected static ?string $pluralLabel = 'اقسام المؤسسة   ';
    protected static ?string $navigationGroup = " الاعدادات ";

    protected static function getNavigationBadge(): ?string
    {
        return Section::count();
    }

    protected static function getNavigationBadgeColor(): ?string
    {
        return "success"; // TODO: Change the autogenerated stub
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\TextInput::make("name")->label("الاسم"),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("id")->label("ID")->sortable(),
                Tables\Columns\TextColumn::make("name")->searchable()->sortable()->label("اسم القسم "),
                Tables\Columns\TextColumn::make('employees_count')->counts('employees')->label("عدد الموظفين"),
                Tables\Columns\BadgeColumn::make('employees_count')->counts('employees')->label('الرتبة')->sortable()->label("عدد الموظفين"),


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->button(),
                Tables\Actions\ViewAction::make()->button(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AttendanceRelationManager::class,
            EmployeesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSections::route('/'),
            'create' => Pages\CreateSection::route('/create'),
            'edit' => Pages\EditSection::route('/{record}/edit'),
        ];
    }
}
