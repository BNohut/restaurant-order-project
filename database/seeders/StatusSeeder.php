<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the default order statuses with their properties
        $statuses = [
            [
                'name' => 'Pending',
                'description' => 'Order has been placed but not yet processed',
                'color' => '#ffc107', // Yellow
                'sort_order' => 10,
            ],
            [
                'name' => 'Approved',
                'description' => 'Order has been approved by the restaurant',
                'color' => '#28a745', // Green
                'sort_order' => 20,
            ],
            [
                'name' => 'Preparing',
                'description' => 'Order is being prepared by the restaurant',
                'color' => '#17a2b8', // Blue
                'sort_order' => 30,
            ],
            [
                'name' => 'Ready for pickup',
                'description' => 'Order is ready for pickup by courier',
                'color' => '#6c757d', // Gray
                'sort_order' => 40,
            ],
            [
                'name' => 'On the way',
                'description' => 'Order is on the way to the customer',
                'color' => '#fd7e14', // Orange
                'sort_order' => 50,
            ],
            [
                'name' => 'Delivered',
                'description' => 'Order has been delivered to the customer',
                'color' => '#28a745', // Green
                'sort_order' => 60,
            ],
            [
                'name' => 'Cancelled',
                'description' => 'Order has been cancelled',
                'color' => '#dc3545', // Red
                'sort_order' => 70,
            ],
            [
                'name' => 'Rejected',
                'description' => 'Order has been rejected by the restaurant',
                'color' => '#dc3545', // Red
                'sort_order' => 80,
            ],
        ];

        // Create or update each status
        foreach ($statuses as $statusData) {
            Status::firstOrCreate(
                ['name' => $statusData['name']],
                [
                    'description' => $statusData['description'],
                    'color' => $statusData['color'],
                    'sort_order' => $statusData['sort_order'],
                ]
            );
        }
    }
}
