<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('m_status_tabs', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('title');
        });

        DB::table('m_status_tabs')->insert(
            array(
                ['title' => 'DRAFT'],
                ['title' => 'NOT ACTIVE'],
                ['title' => 'ACTIVE'],
                ['title' => 'HOT'],
                ['title' => 'TANYA-TANYA'],
                ['title' => 'HANYA CHAT'],
                ['title' => 'BOOKING'],
                ['title' => 'VISIT'],
                ['title' => 'CANCEL VISIT'],
                ['title' => 'BI CHECKING'],
                ['title' => 'AKAD'],
                ['title' => 'PEMBERKASAN'],
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_status_tabs');
    }
};
