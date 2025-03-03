<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->string('slug')->unique()->after('program_name');
        });
    }
    
    public function down()
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
    };
