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
        Schema::create('actual_salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId("employee_id")->constrained()->cascadeOnDelete();
            $table->foreignId("report_id")->constrained()->cascadeOnDelete();
            $table->float("total")->default(0.0)->comment("الراتب الفعلي");
            $table->float("discount")->default(0.)->comment("الخصم");
            $table->float("additional")->default(0.0)->comment("الااضافي");
            $table->float("award")->default(0.0)->comment("مكافئة");
            $table->float("penalty")->default(0.0)->comment("عقوبة");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actual_salaries');
    }
};
