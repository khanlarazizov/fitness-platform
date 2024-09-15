<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\StatusEnum;
use App\Enums\GenderEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('slug');
            $table->string('email');
            $table->string('phone_number');
            $table->string('password');
            $table->date('birth_date');
            $table->enum('status', array_column(StatusEnum::cases(), 'value'))->default(StatusEnum::ACTIVE->value);
            $table->enum('gender', array_column(GenderEnum::cases(), 'value'));
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['slug', 'email', 'phone_number', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainers');
    }
};
