<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    public function definition(): array
    {
        $type = fake()->randomElement(['incoming', 'outgoing']);
        $prefix = $type === 'incoming' ? 'TR-IN-' : 'TR-OUT-';

        return [
            'transaction_number' => $prefix . date('Ymd') . '-' . Str::upper(Str::random(5)),
            'user_id' => User::factory()->state(['role' => 'staff']),
            'type' => $type,
            'status' => 'pending',
            'notes' => fake()->sentence(),
            'approved_by' => null,
            'approved_at' => null,
            'supplier_id' => $type === 'incoming' ? User::factory()->state(['role' => 'supplier']) : null,
            'customer_name' => $type === 'outgoing' ? fake()->name() : null,
        ];
    }
}