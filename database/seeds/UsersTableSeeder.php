<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Create one user for every role
     *
     * @return void
     */
    public function run()
    {
        $roles = \App\Role::getAllSystemRoles();

        //$this->seedOnlyOwner();

        foreach ($roles as $role) {
            $ucfRole = ucfirst($role);
            $u = factory(App\User::class, $role)->create([
                'name' => $ucfRole,
                'email' => $role . '@' . $role . '.dev',
                'password' => bcrypt($role),
                ]);
            $u->companies()->save(
                factory(App\Company::class, 1)->make([
                    'name' => $ucfRole . '\'s Company'    // = Owner's Company
                    ])
            );
        }
        
    }

    public function seedOnlyOwner()
    {
        $owner = factory(App\User::class, 'owner')->create([
            'name' => 'Owner',
            'email' => 'owner@owner.dev',
            'password' => bcrypt('pA$word'),
            ]);
        $owner->companies()->save(
            factory(App\Company::class, 1)->make([
                'name' => ucfirst('owner') . '\'s Company'    // = Owner's Company
                ])
        );
    }
}
