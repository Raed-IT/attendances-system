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
        Schema::table("reports", function (Blueprint $table) {
            $table->enum("permanence_type", [
                \App\Enums\PermanenceTypeEnum::CONSTANT->value,
                \App\Enums\PermanenceTypeEnum::ADMINISTRATIVE->value,
                \App\Enums\PermanenceTypeEnum::SHIFT->value,
            ]);
        }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
