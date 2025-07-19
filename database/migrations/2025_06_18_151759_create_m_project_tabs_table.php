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
        Schema::create('m_project_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_company_tabs_id');
            $table->string('title');
            $table->foreign('m_company_tabs_id')->references('id')->on('m_company_tabs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_project_tabs');
    }
};
