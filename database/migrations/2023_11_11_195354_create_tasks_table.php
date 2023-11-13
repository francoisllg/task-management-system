<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Src\Task\Domain\Enum\TaskStatusEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $cases = array_column(TaskStatusEnum::cases(), 'value');
        Schema::create('tasks', function (Blueprint $table) use ($cases) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('status', $cases)->default(TaskStatusEnum::PENDING->value);
            $table->timestamps();

            $table->foreignId('user_id')
                ->nullable()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->index(['user_id', 'name']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
