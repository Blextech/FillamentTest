<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanCalculatorResource\Pages;
use App\Filament\Resources\LoanCalculatorResource\RelationManagers;
use App\Models\LoanCalculator;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LoanCalculatorResource extends Resource
{
    protected static ?string $model = LoanCalculator::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make()->steps([
                    Forms\Components\Wizard\Step::make('Step 1')
                        ->schema([
                            Forms\Components\TextInput::make('student_name')
                                ->label('Student Name')
                                ->required(),
                            Forms\Components\TextInput::make('tuition_fees')
                                ->label('Tuition Fees')->afterStateUpdated(function ($state, Forms\get $get, $set) {
                                    $totalloan = $state + $get('other_fees') ?? 0;
                                    $set('total_loan_amount', $totalloan);
                                })
                                ->required()
                                ->numeric(),

                            Forms\Components\TextInput::make('other_fees')
                                ->label('Other Fees')->afterStateUpdated(function ($state, Forms\get $get, $set) {
                                    $totalloan = $state + $get('tuition_fees') ?? 0;
                                    $set('total_loan_amount', $totalloan);
                                })
                                ->required()
                                ->numeric(),
                        ]),
                    Forms\Components\Wizard\Step::make('Step 2')
                        ->schema([
                            Forms\Components\TextInput::make('total_loan_amount')
                                ->label('Toal Loan')
                                ->required()
                                ->numeric()->readOnly(),
                            Forms\Components\TextInput::make('interest_rate')->reactive()
                                ->afterStateUpdated(function ($state, Forms\get $get, $set) {
                                    $interestRate = $state / 100;
                                    $totalInterest = $get('total_loan_amount') ?? 0 * $interestRate * ($get('tenure') ?? 0 / 12);
                                    $monthlyRepayment = ($get('total_loan_amount') ?? 0 + $totalInterest) / $get('tenure') ?? 0;
                                    $totalRepayment = $get('total_loan_amount') ?? 0 + $totalInterest;

                                    $set('total_interest', $totalInterest);
                                    $set('monthly_loan_repayment', $monthlyRepayment);
                                    $set('total_loan_repayment', $totalRepayment);
                                })
                                ->label('Rate')->default(20)
                                ->required()
                                ->numeric(),
                            Forms\Components\Select::make('tenure')->options([
                                12 => '12 Months',
                                18 => '18 Months',
                            ])->reactive()
                                ->afterStateUpdated(function ($state, Forms\get $get, $set) {
                                    $interestRate = $get('interest_rate') ?? 0 / 100;
                                    $totalInterest = $get('total_loan_amount') ?? 0 * $interestRate * ($state / 12);
                                    $monthlyRepayment = ($get('total_loan_amount') ?? 0 + $totalInterest) / $state;
                                    $totalRepayment = $get('total_loan_amount') ?? 0 + $totalInterest;

                                    $set('total_interest', $totalInterest);
                                    $set('monthly_loan_repayment', $monthlyRepayment);
                                    $set('total_loan_repayment', $totalRepayment);
                                })
                                ->label('Tenure')
                                ->required(),
                            Forms\Components\TextInput::make('total_interest')
                                ->required()
                                ->numeric()->readOnly()->hidden(),
                            Forms\Components\TextInput::make('monthly_loan_repayment')
                                ->required()
                                ->numeric()->readOnly()->hidden(),
                            Forms\Components\TextInput::make('total_loan_repayment')
                                ->required()
                                ->numeric()->readOnly()->hidden(),
                        ])

                ])




            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student_name'),
                Tables\Columns\TextColumn::make('tuition_fees'),
                Tables\Columns\TextColumn::make('other_fees'),
                Tables\Columns\TextColumn::make('total_loan_amount'),
                Tables\Columns\TextColumn::make('interest_rate'),
                Tables\Columns\TextColumn::make('tenure'),
                Tables\Columns\TextColumn::make('monthly_loan_repayment'),
                Tables\Columns\TextColumn::make('total_interest'),
                Tables\Columns\TextColumn::make('total_loan_repayment'),

            ])

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    // protected function Submit(array $data): void
    // {
    //     // dd($data);
    //     // $loanCalculator = new LoanCalculator();
    //     // $loanCalculator->student_name = $data['student_name'];
    //     // $loanCalculator->tuition_fees = $data['tuition_fees'];
    //     // $loanCalculator->other_fees = $data['other_fees'];
    //     // $loanCalculator->total_loan_amount = $data['total_loan_amount'];
    //     // $loanCalculator->interest_rate = $data['interest_rate'];
    //     // $loanCalculator->tenure = $data['tenure'];
    //     // $loanCalculator->monthly_loan_repayment = $data['monthly_loan_repayment'];
    //     // $loanCalculator->total_interest = $data['total_interest'];
    //     // $loanCalculator->total_loan_repayment = $data['total_loan_repayment'];
    //     // $loanCalculator->save();
    //     LoanCalculator::create(
    //         [
    //             'student_name' => 'hello',
    //             'tuition_fees' => 'hello',
    //             'other_fees'  => 'hello',
    //             'total_loan_amount' => 'hello',
    //             'interest_rate' => 'hello',
    //             'tenure' => 'hello',
    //             'total_interest' => 'hello',
    //             'monthly_loan_repayment' => 'hello',
    //             'total_loan_repayment' => 'hello',
    //         ]
    //     );
    // }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLoanCalculators::route('/'),
            'create' => Pages\CreateLoanCalculator::route('/create'),
            'edit' => Pages\EditLoanCalculator::route('/{record}/edit'),
        ];
    }
}
