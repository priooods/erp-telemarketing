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
        Schema::create('t_document_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('t_finance_tabs_id');
            $table->string('filename');
            $table->text('path');
            $table->text('catatan')->nullable();
            $table->unsignedInteger('m_status_tabs_id');
            $table->timestamps();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('t_finance_tabs_id')->references('id')->on('t_finance_tabs')->cascadeOnDelete();
            $table->foreign('m_status_tabs_id')->references('id')->on('m_status_tabs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_document_tabs');
    }
};
