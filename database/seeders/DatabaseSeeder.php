<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Group;
use App\Models\Contribution;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database based on Northern Home of Hope structural rules.
     */
    public function run(): void
    {
        // 1. Create or update your personal Administrative user profile
        $admin = User::updateOrCreate(
            ['email' => 'admin@hope.org'], // Target identifier match check
            [
                'name'              => 'System Administrator',
                'phone_number'      => '+254700000000',
                'county'            => 'Nairobi',
                'sub_county'        => 'Central',
                'ward'              => 'Administrative',
                'role'              => 'admin', // 👈 Explicitly marked as admin
                'password'          => Hash::make('password'), // Your fallback access key
                'email_verified_at' => now(),
            ]
        );

        // 2. Generate your 15 groups
        $groups = [];
        for ($i = 1; $i <= 15; $i++) {
            $groups[] = Group::create([
                'name' => "Group " . sprintf("%02d", $i),
                'leader_id' => null, // Will assign after members are generated cleanly
            ]);
        }

        // 3. Generate the remaining 163 members to total 164 users
        $members = User::factory()->count(163)->create([
            'role' => 'member' // 👈 Force-default factory users to standard member status initially
        ]);

        // 4. Distribute the 163 members evenly across your 15 groups
        $groupIndex = 0;
        foreach ($members as $member) {
            $currentGroup = $groups[$groupIndex];
            
            $member->update([
                'group_id' => $currentGroup->id
            ]);

            // Move to the next group in a round-robin rotation pattern
            $groupIndex = ($groupIndex + 1) % 15;
        }

        // 5. Appoint a Representative/Leader for each of the 15 groups from its member pool
        foreach ($groups as $group) {
            $firstGroupMember = User::where('group_id', $group->id)->first();
            if ($firstGroupMember) {
                // Set the group leader linkage
                $group->update([
                    'leader_id' => $firstGroupMember->id
                ]);

                // Upgrade this specific member's authority privileges to leader status!
                $firstGroupMember->update([
                    'role' => 'leader' // 👈 Promoted to leader
                ]);
            }
        }

        // 6. Generate simulated contribution financial rows linked directly to these members
        foreach ($members as $member) {
            Contribution::create([
                'user_id' => $member->id,
                'amount' => 100.00, // Every member contributes 100 base weekly currency units
                'purpose' => fake()->randomElement(['Weekly Contribution', 'Hospital Bill Support', 'School Fee Balance Support', 'Registration Fee']),
                'status' => fake()->randomElement(['completed', 'pending']),
            ]);
        }
    }
}