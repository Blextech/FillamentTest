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
        Schema::create('loancalculators', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->double('tuition_fees', 10, 2);
            $table->double('other_fees', 10, 2);
            $table->double('total_loan_amount', 10, 2);
            $table->double('interest_rate', 5, 2);
            $table->integer('tenure');
            $table->double('total_interest', 10, 2);
            $table->double('monthly_loan_repayment', 10, 2);
            $table->double('total_loan_repayment', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropDatabaseIfExists('loancalculators');
    }
};
