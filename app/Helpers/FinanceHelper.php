<?php

namespace App\Helpers;

use App\Models\FinanceCashFlow;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use RuntimeException;

class FinanceHelper
{
    public static function add_cashflow($finance_account_id, $debit, $credit, $code, $transaction_number, $note, $created_by, $date)
    {
        $finance_cash_flow = new FinanceCashFlow;
        $finance_cash_flow->source = FinanceCashFlow::SOURCE_BY_SYSTEM;
        $finance_cash_flow->account_id = $finance_account_id;
        $finance_cash_flow->code = $code;
        $finance_cash_flow->transaction_number = $transaction_number;
        $finance_cash_flow->transaction_date = $date;
        $finance_cash_flow->note = $note;
        $finance_cash_flow->debit = $debit;
        $finance_cash_flow->credit = $credit;
        $finance_cash_flow->account_id = $finance_account_id;
        $finance_cash_flow->created_by = $created_by;
        $finance_cash_flow->save();
    }

}
