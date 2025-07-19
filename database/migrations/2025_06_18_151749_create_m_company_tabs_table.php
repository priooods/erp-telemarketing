<?php

use Carbon\Carbon;
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
        Schema::create('m_company_tabs', function (Blueprint $table) {
            $table->id();
            $table->date('access_date');
            $table->string('company_name');
            $table->string('phone');
            $table->string('npwp');
            $table->string('address');
            $table->timestamps();
        });

        DB::table('m_company_tabs')->insert(
            [
                'access_date' => Carbon::now(),
                'company_name' => 'PT. Intan Sejahtera',
                'phone' => '62891234567',
                'npwp' => '5234343132',
                'address' => 'dsfadasvasf',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_company_tabs');
    }
};
