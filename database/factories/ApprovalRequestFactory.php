<?php

namespace Database\Factories;

use App\Models\ApprovalRequest;
use App\Models\User;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApprovalRequestFactory extends Factory
{
    protected $model = ApprovalRequest::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'created_by' => User::factory(),
            'client_id' => Client::factory(),
            'title' => fake()->sentence(6),
            'description' => fake()->paragraphs(3, true),
            'version' => 'v1',
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'client_name' => fake()->name(), // Legacy field
            'client_email' => fake()->safeEmail(), // Legacy field
            'status' => 'pending',
            'approved_at' => null,
            'rejected_at' => null,
            'internal_notes' => fake()->optional()->paragraph(),
        ];
    }

    /**
     * Indicate that the approval is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'approved_at' => now(),
        ]);
    }

    /**
     * Indicate that the approval is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'rejected_at' => now(),
        ]);
    }

    /**
     * Set a specific version.
     */
    public function version(string $version): static
    {
        return $this->state(fn (array $attributes) => [
            'version' => $version,
        ]);
    }

    /**
     * Set a specific client.
     */
    public function forClient(string $name, string $email): static
    {
        return $this->state(fn (array $attributes) => [
            'client_name' => $name,
            'client_email' => $email,
        ]);
    }
}
