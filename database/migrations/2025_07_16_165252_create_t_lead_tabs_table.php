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
        Schema::create('t_lead_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('m_project_tabs_id');
            $table->unsignedInteger('m_status_tabs_id');
            $table->string('customer_nama')->nullable();
            $table->string('customer_phone');
            $table->text('customer_address');
            $table->date('lead_in');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('m_project_tabs_id')->references('id')->on('m_project_tabs')->cascadeOnDelete();
            $table->foreign('m_status_tabs_id')->references('id')->on('m_status_tabs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_lead_tabs');
    }
};
