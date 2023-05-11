<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Device;
use App\Models\Employee;
use App\Traits\SendNotificationsTrait;
use Faker\Provider\Text;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Rats\Zkteco\Lib\ZKTeco;
use Illuminate\Database\Eloquent\Collection;


class EmployeeResource extends Resource
{
    use  SendNotificationsTrait;

    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\TextInput::make("name")->label("اسم الموضف ")->required()->unique(),
                    Forms\Components\TextInput::make("uid")->label("uid")->required()->unique(),
                    Forms\Components\TextInput::make("userid")->label("uid")->required()->unique(),
                    Forms\Components\TextInput::make("role")->unique(),
                    Forms\Components\TextInput::make("password")->label("uid")->unique(),
                    Forms\Components\TextInput::make("cardno")->label("uid")->unique(),
                ])]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name")->label("اسم الموضف ")->searchable()->sortable(),
                Tables\Columns\TextColumn::make("uid")->label("uid")->searchable(),
                Tables\Columns\TextColumn::make("userid")->label("userid")->sortable()->searchable(),
                Tables\Columns\TextColumn::make("role")->label("role"),
                Tables\Columns\TextColumn::make("password")->label("password"),
                Tables\Columns\TextColumn::make("cardno")->label("cardno")->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make("delete")
                    ->requiresConfirmation()
                    ->action(function (Employee $record) {
                        self:: removeEmployee($record);
                    })
                    ->label("حذف ")->button()->color("danger"),

            ])
            ->bulkActions([
                BulkAction::make('delete')
                    ->requiresConfirmation()
                    ->action(function (Collection $records) {

                        $zk = new ZKTeco("192.168.1.201");
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


