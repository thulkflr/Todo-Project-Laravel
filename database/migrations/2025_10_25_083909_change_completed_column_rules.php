<?php

use App\Enums\TodoStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('todo_model', function (Blueprint $table) {
            $table->dropColumn('completed');
        });

        Schema::table('todo_model', function (Blueprint $table) {
            $table->string('completed')->default(TodoStatus::PENDING_TASK->value);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('todo_model', function (Blueprint $table) {
            $table->dropColumn('completed');
        });
    }
};
