<?php

namespace Stevie\Warden\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Stevie\Warden\Models\Capability;
use Stevie\Warden\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Capability>
 */
class CapabilityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = Capability::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(8, true);
        $slug = Str::slug($name);

        return [
            'name' => $name,
            'slug' => $slug,
        ];
    }

    /**
     * Create a role that grants the capability.
     */
    public function withRole(): static
    {
        return $this->hasAttached(Role::factory());
    }
}
