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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("uid")->nullable();
            $table->bigInteger("userid")->nullable()->unsigned();
            $table->bigInteger("device_id")->nullable()->unsigned();
            $table->bigInteger("namerole")->nullable();
            $table->boolean("role")->nullable();
            $table->string("password")->nullable();
            $table->string("cardno")->nullable();
            $table->string("name")->nullable();
            $table->boolean("has_fingerprint")->default(false);
            $table->bigInteger("bank_no")->nullable();
            $table->foreignId("salary_id")->nullable()->constrained('salaries')->nullOnDelete();
            $table->enum("permanence_type", [
                \App\Enums\PermanenceTypeEnum::ADMINISTRATIVE->value,
                \App\Enums\PermanenceTypeEnum::SHIFT->value,
                \App\Enums\PermanenceTypeEnum::CONSTANT->value,
            ])->default(\App\Enums\PermanenceTypeEnum::ADMINISTRATIVE->value)->comment("نوع الدوام ")->nullable();
            $table->string("job_description")->comment("الوصف الوظيفي ")->nullable();

            $table->unique(["userid", "device_id"]);
            $table->foreignId("section_id")->nullable()->constrained('sections')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
