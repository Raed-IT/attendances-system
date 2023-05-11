<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->enum("type",
                [\App\Enums\PermanenceTypeEnum::ADMINISTRATIVE->value,
                    \App\Enums\PermanenceTypeEnum::SHIFT->value,
                    \App\Enums\PermanenceTypeEnum::CONSTANT->value
                ]);
            $table->float("price");
            $table->integer("count_of_shift")->nullable()->comment("عدد الساعات لحساب واحد من قيمة الراتب ");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
