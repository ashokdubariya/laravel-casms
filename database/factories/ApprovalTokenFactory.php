<?php

namespace Database\Factories;

use App\Models\ApprovalToken;
use App\Models\ApprovalRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApprovalTokenFactory extends Factory
{
    protected $model = ApprovalToken::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'approval_request_id' => ApprovalRequest::factory(),
            'token' => ApprovalToken::generateSecureToken(),
            'expires_at' => now()->addDays(7),
            'used_at' => null,
            'ip_address' => null,
            'user_agent' => null,
        ];
    }

    /**
     * Indicate that the token is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->subDays(1),
        ]);
    }

    /**
     * Indicate that the token has been used.
     */
    public function used(): static
    {
        return $this->state(fn (array $attributes) => [
            'used_at' => now(),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ]);
    }

    /**
     * Set custom expiry (in days from now).
     */
    public function expiresIn(int $days): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->addDays($days),
        ]);
    }
}
