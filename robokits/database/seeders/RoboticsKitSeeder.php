<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoboticsKit;

class RoboticsKitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RoboticsKit::create([
            'name' => 'StarterKit',
            'description' => 'Basic robotics kit for beginners',
            'price' => 299.99,
            'stock' => 50
        ]);

        RoboticsKit::create([
            'name' => 'Educational Robotics Kit',
            'description' => 'Comprehensive kit for educational purposes',
            'price' => 599.99,
            'stock' => 25
        ]);

        RoboticsKit::create([
            'name' => 'Kit5',
            'description' => 'Advanced robotics kit level 5',
            'price' => 999.99,
            'stock' => 15
        ]);
    }
}
