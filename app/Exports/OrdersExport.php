<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Order::with(['user', 'user.currency', 'user.feeGroup'])->get();
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'User Name',
            'User ID In Application',
            'Product',
            'Amount',
            'Created At',
            'Order Type',
            'Currency',
            'Price',
            'Fee',
            'Total',
            'Balance',
            'Debit Balance',
            'Updated At',
            'Status',
        ];
    }

    public function map($order): array
    {
        $feeGroup = $order->user->feeGroup;
        $feePercentage = $feeGroup->fee ?? 10; // Assuming 10% as default fee
        $feeAmount = ($feePercentage / 100) * $order->item_price;

        return [
            $order->id,
            $order->user->name,
            $order->service_id ?? '',
            $order->item_name . ' - ' . $order->sub_item_name ?? '',
            $order->amount ?? '',
            $order->created_at->format('Y-m-d H:i:s'),
            $order->order_type,
            $order->user->currency->currency ?? 'USD',
            $order->item_price ?? '',
            number_format($feeAmount, 2) . ' ' . ($order->user->currency->currency ?? 'USD'),
            $order->total,
            $order->wallet_before ?? '' . ' ' . ($order->user->currency->currency ?? 'USD'),
            $order->wallet_after ?? '' . ' ' . ($order->user->currency->currency ?? 'USD'),
            $order->updated_at->format('Y-m-d H:i:s'),
            ucfirst($order->status),
        ];
    }
}
