<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loancalculator extends Model
{
    // Using Filament, create a LoanCalculator Resource, 
    // use filament form wizard for the form with the following: Step 1: Student Name, Tuition Fees ,
    //  Other Fees Step 2: Total Loan Amount, Interest Rate (20%), & Tenure (12, 18 months)... 
    //  Let the Total Loan be auto populated based on the sum of Tuition Fees & Other Fees from step 1.
    //   On submission calculate (Total Interest, Monthly Loan Repayment & Total Loan Repayment) and store all records to DB, 
    //   then display all records on ListRecord table

    use HasFactory;

    protected $fillable = [
        'student_name',
        'tuition_fees',
        'other_fees',
        'total_loan_amount',
        'interest_rate',
        'tenure',
        'total_interest',
        'monthly_loan_repayment',
        'total_loan_repayment',
    ];  //creating the neccessary model so i will be able to save to database
}
