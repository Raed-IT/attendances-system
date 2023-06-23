<?php

namespace App\Filament\Resources;

use App\Enums\EmployeeDeviceRoleEnum;
use App\Enums\PermanenceTypeEnum;
use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Device;
use App\Models\Employee;
use App\Models\Salary;
use App\Traits\SendNotificationsTrait;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use Rats\Zkteco\Lib\ZKTeco;
use Livewire\Component as Livewire;

use Filament\Resources\Pages\CreateRecord;
use Closure;

class EmployeeResource extends Resource
{
    use  SendNotificationsTrait;

    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $label = 'موظف     ';
    protected static ?string $pluralLabel = 'الموظفين ';
    protected static ?string $navigationGroup = " التقارير ";

    public static function form(Form $form): Form
    {


        return $form
            ->schema([
                Forms\Components\Card::make()->schema([

                    Forms\Components\TextInput::make("name")->label("اسم الموضف ")->required(),//->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make("uid")->label(" uid")->disabled(),

                    Forms\Components\Select::make("device_id")
                        ->options(function () {
                            return Device::all()->pluck("name", "id");
                        })->label("الجهاز")
                        ->reactive()->required()->afterStateUpdated(function (Closure $set, $state, Livewire $livewire) {
                            if ($livewire instanceof CreateRecord) {
                                $employees = Employee::whereDeviceId($state)->get();
                                $max = $employees->max('uid');
                                $min = 1;
                                $standerArray = range($min, $max);
                                $missing = array_diff($standerArray, $employees->pluck("uid")->toArray());
                                if ($missing) {
                                    $set('uid', min($missing));
                                } else {
                                    $set('uid', $max + 1);
                                }
                            }
                        }),

                    Forms\Components\Select::make("section_id")
                        ->relationship("section", "name")->label("القسم")->required(),

                    Forms\Components\Select::make("role")->options(EmployeeDeviceRoleEnum::values())->default(EmployeeDeviceRoleEnum::USER->value),

                    Forms\Components\TextInput::make("userid")->label("ID المستخدم")
                        ->required()->unique(ignoreRecord: true)->maxLength(8)->numeric()->mask(fn(Mask $mask) => $mask->numeric())->default(function () {
                            $employees = Employee::all();
                            $max = $employees->max('userid');
                            $min = 1;
                            $standerArray = range($min, $max);
                            $missing = array_diff($standerArray, $employees->pluck("userid")->toArray());
                            if ($missing) {
                                return min($missing);
                            } else {
                                return $max + 1;
                            }
                        }),


                    Forms\Components\TextInput::make("password")->label("كلمة السر")->mask(fn(Mask $mask) => $mask->numeric())->maxLength(8),

                    Forms\Components\TextInput::make("bank_no")->label("رقم بطاقة البنك")
                        ->unique(ignoreRecord: true)->mask(fn(Mask $mask) => $mask->numeric()),


                    Forms\Components\Select::make("permanence_type")
                        ->options(PermanenceTypeEnum::values()->all())->required()->label("نوع الدوام")
                        ->reactive()->afterStateUpdated(fn(callable $set) => $set("salary_id", null)),

                    Forms\Components\Select::make("salary_id")->options(function (callable $get) {
                        $data = [];
                        if ($get('permanence_type')) {
                            $salaries = Salary::whereType($get('permanence_type'))->get();
                            foreach ($salaries as $salary) {
                                $data += [$salary->id => PermanenceTypeEnum::tryFrom($salary->type)->name() . $salary->count_of_shift . "ساعة" . $salary->price . "$"];
                            }
                        }
                        return $data;
                    })->required()->label("نوع الراتب"),
                ])]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("id")->label("ID")->searchable()->sortable(),
                Tables\Columns\TextColumn::make("name")->label("اسم الموضف ")->searchable()->sortable()->copyable(),
                Tables\Columns\BadgeColumn::make("device.name")->label("البصامة")->sortable(),
                Tables\Columns\BadgeColumn::make("role")
                    ->formatStateUsing(fn($state) => EmployeeDeviceRoleEnum::tryFrom($state)?->name())
                    ->colors(EmployeeDeviceRoleEnum::colors()->all())
                    ->label("صلاحية الموظف")->sortable(),

                Tables\Columns\TextColumn::make("userid")->label("معرف الموظف ")->sortable()->searchable(),
                Tables\Columns\IconColumn::make("has_fingerprint")->label("يملك بصمة")->boolean(),
                Tables\Columns\IconColumn::make("te")->label("يملك سجل دوام")->boolean()->getStateUsing(function ($record) {
                    return $record->attendances->isNotEmpty();
                }),


                Tables\Columns\BadgeColumn::make('salary_id')->formatStateUsing(function ($state) {
                    if (!is_null($state)) {
                        $salary = Salary::find($state);
                        return PermanenceTypeEnum::tryFrom($salary->type)->name() . '  ' . $salary->count_of_shift . "ساعة " . $salary->price . "$";
                    }
                    return "";
                })->label('نوع الراتب')->sortable(),
            ])
            ->filters([
                TernaryFilter::make('has_fingerprint')->label('يملك بصمة'),
                SelectFilter::make('section_id')
                    ->relationship("section", "name")->label("فلتر بحسب القسم "),
                SelectFilter::make('salary_id')
                    ->options(function () {
                        $data = [];
                        $salaries = Salary::all();
                        foreach ($salaries as $salary) {
                            $data += [$salary->id => PermanenceTypeEnum::tryFrom($salary->type)->name() . "  " . $salary->count_of_shift . "ساعة" . $salary->price . "$"];
                        }
                        return $data;
                    })->label("فلتر بحسب الراتب ")

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make("delete")
                    ->requiresConfirmation()
                    ->modalSubheading(fn(Employee $record) => "سيتم حذف الموضف " . $record->name)
                    ->action(function (Employee $record) {
                        self:: removeEmployee($record);
                    })
                    ->label("حذف ")->button()->color("danger"),

            ])
            ->bulkActions([
                BulkAction::make('delete')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Select::make("deviceId")->options(function () {
                            return Device::all()->pluck("name", "id");
                        })->label("البصامة")->required(),

                    ])
                    ->action(function (Collection $records, $data) {
                        $device = Device::find($data['deviceId']);
                        $zk = new ZKTeco($device->ip);
                        if ($zk->connect()) {
                            $zk->enableDevice();
                            $count = count($records);
                            try {
                                foreach ($records as $record) {
                                    $zk->removeUser($record->uid);
                                    $record->delete();
                                }
                                $notification = Notification::make()->title("تم حذف " . $count . " موظف ")->success();
                                $notification->send();
                                $notification->toDatabase();
                                $zk->disableDevice();
                            } catch (\Exception $e) {
                                $notification = Notification::make()->title("تم فشل في الحذف ")->success();
                                $notification->send();
                                $notification->toDatabase();
                                $zk->disableDevice();
                            }
                        } else {
                            $notification = Notification::make()->title("لايوجد اتصال ")->success();
                            $notification->send();
                            $notification->toDatabase();

                        }
                    })
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }

    public static function removeEmployee(Employee $record)
    {
        $device = Device::find($record->device_id);
        $zk = new ZKTeco($device->ip);

        if ($zk->connect()) {
            $zk->enableDevice();
            $zk->removeUser($record->uid);
            $zk->disableDevice();
            $record->delete();
            $notification = Notification::make()->title("تم حذف الموظف " . $record->name)->success();
            $notification->send();
            $notification->toDatabase();
        } else {
            $notification = Notification::make()->title("لم يتم الاتصال بالجهاز " . $record->name)->success();
            $notification->send();
        }
    }
}


