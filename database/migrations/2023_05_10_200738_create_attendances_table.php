<?php

use App\Enums\AttendanceTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string("uid")->nullable();
//            $table->string("id")->nullable();
            $table->date("timestamp");
            $table->enum("state", [AttendanceTypeEnum::CHECK_IN->value, AttendanceTypeEnum::CHECK_OUT->value]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
