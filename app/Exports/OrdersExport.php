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
        return Order::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'User',
            'Service ID',
            'Order Type',
            'Created At',
            'Updated At',
            'Total',
            'Status',
        ];
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->user->name,
            $order->subItems[0]->service_id ?? '',
            $order->order_type,
            $order->created_at,
            $order->updated_at,
            $order->total,
            ucfirst($order->status),
        ];
    }
}
