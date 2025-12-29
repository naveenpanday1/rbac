<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Role;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => \Str::random(10),

            'company_id' => Company::factory(),
            'role_id'    => Role::factory(),
        ];
    }

    public function superAdmin()
    {
        return $this->state(fn () => [
            'role_id' => Role::where('name', 'superadmin')->first()->id,
        ]);
    }

    public function admin()
    {
        return $this->state(fn () => [
            'role_id' => Role::where('name', 'admin')->first()->id,
        ]);
    }

    public function member()
    {
        return $this->state(fn () => [
            'role_id' => Role::where('name', 'member')->first()->id,
        ]);
    }
     public function unverified()
    {
        return $this->state(fn () => [
            'email_verified_at' => null,
        ]);
    }
}
