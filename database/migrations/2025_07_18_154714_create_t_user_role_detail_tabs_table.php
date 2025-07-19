<?php

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
        Schema::create('t_user_role_detail_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('m_user_role_tabs_id');
            $table->unsignedBigInteger('users_id');
            $table->foreign('m_user_role_tabs_id')->references('id')->on('m_user_role_tabs')->cascadeOnDelete();
            $table->foreign('users_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_user_role_detail_tabs');
    }
};
