// database/migrations/YYYY_MM_DD_create_progress_items_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgressItemsTable extends Migration
{
    public function up()
    {
        Schema::create('progress_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained()->onDelete('cascade');
            $table->string('surah');
            $table->boolean('is_completed')->default(false);
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('progress_items');
    }
}