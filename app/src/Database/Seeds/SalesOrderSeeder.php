<?php

declare(strict_types=1);

/*
 * UserFrosting Commerce Sprinkle (http://www.userfrosting.com)
 *
 * @link      https://github.com/ssnukala/sprinkle-commerce
 * @copyright Copyright (c) 2025 Srinivas Nukala
 * @license   https://github.com/ssnukala/sprinkle-commerce/blob/main/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\Commerce\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Seeder for sales orders
 */
class SalesOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        $orders = [
            [
                'year' => 2025,
                'name' => 'Order #SO-001',
                'description' => 'Electronics purchase for home office',
                'order_number' => 'SO-2025-001',
                'contract_number' => null,
                'order_status' => 'completed',
                'type' => 'retail',
                'parent_id' => null,
                'user_id' => 1,
                'approver_id' => null,
                'order_date' => $now->copy()->subDays(30),
                'expiry_date' => null,
                'net_amount' => 1379.96,
                'tax' => 110.40,
                'discount' => 0.00,
                'epay_commission' => 0.00,
                'gross_amount' => 1490.36,
                'payment_type' => 'credit_card',
                'payment_ref' => 'CC-12345',
                'payment_link' => null,
                'payment_date' => $now->copy()->subDays(30),
                'payment_note' => 'Paid in full',
                'notes' => 'Customer requested expedited shipping',
                'status' => 'A',
            ],
            [
                'year' => 2025,
                'name' => 'Order #SO-002',
                'description' => 'Bulk office supplies order',
                'order_number' => 'SO-2025-002',
                'contract_number' => null,
                'order_status' => 'completed',
                'type' => 'wholesale',
                'parent_id' => null,
                'user_id' => 1,
                'approver_id' => null,
                'order_date' => $now->copy()->subDays(25),
                'expiry_date' => null,
                'net_amount' => 799.98,
                'tax' => 64.00,
                'discount' => 50.00,
                'epay_commission' => 0.00,
                'gross_amount' => 813.98,
                'payment_type' => 'invoice',
                'payment_ref' => 'INV-2025-002',
                'payment_link' => null,
                'payment_date' => $now->copy()->subDays(20),
                'payment_note' => 'Net 30 payment terms',
                'notes' => 'Business customer - approved credit',
                'status' => 'A',
            ],
            [
                'year' => 2025,
                'name' => 'Order #SO-003',
                'description' => 'Gaming setup complete',
                'order_number' => 'SO-2025-003',
                'contract_number' => null,
                'order_status' => 'pending',
                'type' => 'retail',
                'parent_id' => null,
                'user_id' => 1,
                'approver_id' => null,
                'order_date' => $now->copy()->subDays(5),
                'expiry_date' => $now->copy()->addDays(25),
                'net_amount' => 819.97,
                'tax' => 65.60,
                'discount' => 0.00,
                'epay_commission' => 0.00,
                'gross_amount' => 885.57,
                'payment_type' => 'paypal',
                'payment_ref' => null,
                'payment_link' => 'https://paypal.com/pay/12345',
                'payment_date' => null,
                'payment_note' => 'Awaiting payment',
                'notes' => 'Hold for payment confirmation',
                'status' => 'P',
            ],
            [
                'year' => 2025,
                'name' => 'Order #SO-004',
                'description' => 'Mobile phone upgrade',
                'order_number' => 'SO-2025-004',
                'contract_number' => null,
                'order_status' => 'completed',
                'type' => 'retail',
                'parent_id' => null,
                'user_id' => 1,
                'approver_id' => null,
                'order_date' => $now->copy()->subDays(15),
                'expiry_date' => null,
                'net_amount' => 799.99,
                'tax' => 64.00,
                'discount' => 100.00,
                'epay_commission' => 0.00,
                'gross_amount' => 763.99,
                'payment_type' => 'credit_card',
                'payment_ref' => 'CC-23456',
                'payment_link' => null,
                'payment_date' => $now->copy()->subDays(15),
                'payment_note' => 'Trade-in discount applied',
                'notes' => 'Customer traded in old device',
                'status' => 'A',
            ],
            [
                'year' => 2025,
                'name' => 'Order #SO-005',
                'description' => 'Photography equipment bundle',
                'order_number' => 'SO-2025-005',
                'contract_number' => null,
                'order_status' => 'shipped',
                'type' => 'retail',
                'parent_id' => null,
                'user_id' => 1,
                'approver_id' => null,
                'order_date' => $now->copy()->subDays(7),
                'expiry_date' => null,
                'net_amount' => 1199.98,
                'tax' => 96.00,
                'discount' => 0.00,
                'epay_commission' => 0.00,
                'gross_amount' => 1295.98,
                'payment_type' => 'credit_card',
                'payment_ref' => 'CC-34567',
                'payment_link' => null,
                'payment_date' => $now->copy()->subDays(7),
                'payment_note' => 'Paid in full',
                'notes' => 'Shipped via express courier',
                'status' => 'A',
            ],
        ];

        foreach ($orders as $order) {
            DB::table('or_sales_order')->insert(array_merge($order, [
                'created_at' => $order['order_date'],
                'updated_at' => $now,
            ]));
        }
    }
}
