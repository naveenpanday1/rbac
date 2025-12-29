<?php

namespace Database\Factories;

use App\Models\ShortUrl;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ShortUrlFactory extends Factory
{
    protected $model = ShortUrl::class;

    public function definition(): array
    {
        $memberRoleId = Role::where('name', 'Member')->value('id');

        $user = User::factory()->create([
            'role_id' => $memberRoleId,
            'company_id' => 1,
        ]);

        return [
            'original_url' => fake()->url(),
            'short_code'   => Str::random(6),
            'company_id'   => $user->company_id,
            'created_by'   => $user->id,
            'created_at'   => now(),
            'updated_at'   => now(),
        ];
    }

    public function createdByAdmin(): static
    {
        return $this->state(function () {
            $adminRoleId = Role::where('name', 'Admin')->value('id');

            $admin = User::factory()->create([
                'role_id' => $adminRoleId,
                'company_id' => 1,
            ]);

            return [
                'company_id' => $admin->company_id,
                'created_by' => $admin->id,
            ];
        });
    }

    public function createdByMember(): static
    {
        return $this->state(function () {
            $memberRoleId = Role::where('name', 'Member')->value('id');

            $member = User::factory()->create([
                'role_id' => $memberRoleId,
                'company_id' => 1,
            ]);

            return [
                'company_id' => $member->company_id,
                'created_by' => $member->id,
            ];
        });
    }

    public function otherCompany(): static
    {
        return $this->state(function () {
            $memberRoleId = Role::where('name', 'Member')->value('id');

            $user = User::factory()->create([
                'role_id' => $memberRoleId,
                'company_id' => 2,
            ]);

            return [
                'company_id' => 2,
                'created_by' => $user->id,
            ];
        });
    }
}
