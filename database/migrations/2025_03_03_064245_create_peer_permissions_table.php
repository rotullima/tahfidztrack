// database/migrations/YYYY_MM_DD_create_peer_permissions_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeerPermissionsTable extends Migration
{
    public function up()
    {
        Schema::create('peer_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grantor_id')->references('id')->on('participants')->onDelete('cascade');
            $table->foreignId('grantee_id')->references('id')->on('participants')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Tidak bisa memberikan izin ke diri sendiri
            $table->unique(['grantor_id', 'grantee_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('peer_permissions');
    }
}