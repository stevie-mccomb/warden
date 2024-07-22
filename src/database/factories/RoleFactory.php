<?php

namespace Stevie\Warden\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Stevie\Warden\Models\Capability;
use Stevie\Warden\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = Role::class;

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
     * Create capabilities to go with the role.
     */
    public function withCapabilities(int $count = 3): static
    {
        return $this->hasAttached(Capability::factory()->count($count));
    }
}
