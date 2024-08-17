<?php

namespace App\Exports;

use App\Models\Account;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class AccountsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Aggregate total amount per client and currency
        $accounts = Account::select('client_id', 'currency_id', DB::raw('SUM(CASE WHEN payment_status = "creditor" THEN -amount WHEN payment_status = "debtor" THEN amount ELSE 0 END) as total_amount'))
            ->groupBy('client_id', 'currency_id')
            ->with('client', 'currency')
            ->get();

        // Map the results for export
        return $accounts->map(function ($account) {
            return [
                'Client Name' => $account->client->name,
                'Client Phone' => $account->client->phone,
                'Currency' => $account->currency->currency ?? 'No Currency',
                'Total Amount' => $account->total_amount,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Client Name',
            'Client Phone',
            'Currency',
            'Total Amount',
        ];
    }
}
