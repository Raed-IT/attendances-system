<?php

namespace Database\Seeders;

 use App\Enums\PermanenceTypeEnum;
 use App\Models\Salary;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Salary::create([
            "price" => 15,
            "type" => PermanenceTypeEnum::SHIFT->value,
            "count_of_shift" => 16
        ]);

        Salary::create([
            "price" => 25,
            "type" => PermanenceTypeEnum::SHIFT->value,
            "count_of_shift" => 16

        ]);
        Salary::create([
            "price" => 30,
            "type" => PermanenceTypeEnum::SHIFT->value,
            "count_of_shift" => 24

        ]);
        Salary::create([
            "price" => 35,
            "type" => PermanenceTypeEnum::SHIFT->value,
            "count_of_shift" => 24
        ]);
        Salary::create([
            "price" => 500,
            "type" => PermanenceTypeEnum::CONSTANT->value,
        ]);
        Salary::create([
            "price" => 1,
            "type" => PermanenceTypeEnum::ADMINISTRATIVE->value,
        ]);
    }
}
