<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

		$permissions = [
            ['name' => 'dashboard menu', 'guard_name' => 'web'],
            ['name' => 'calendar menu', 'guard_name' => 'web'],
            ['name' => 'files menu', 'guard_name' => 'web'],
            ['name' => 'information menu', 'guard_name' => 'web'],
            ['name' => 'sources menu', 'guard_name' => 'web'],
            ['name' => 'people menu', 'guard_name' => 'web'],
            ['name' => 'people index', 'guard_name' => 'web'],
            ['name' => 'people create', 'guard_name' => 'web'],
            ['name' => 'people edit', 'guard_name' => 'web'],
            ['name' => 'people delete', 'guard_name' => 'web'],
            ['name' => 'family menu', 'guard_name' => 'web'],
            ['name' => 'references menu', 'guard_name' => 'web'],
            ['name' => 'trees menu', 'guard_name' => 'web'],
            ['name' => 'forum menu', 'guard_name' => 'web'],
            ['name' => 'gedcom import menu', 'guard_name' => 'web'],
            ['name' => 'subscription menu', 'guard_name' => 'web'],
            ['name' => 'dna upload menu', 'guard_name' => 'web'],
            ['name' => 'dna matching menu', 'guard_name' => 'web'],
            ['name' => 'how to videos menu', 'guard_name' => 'web'],
            ['name' => 'roles menu', 'guard_name' => 'web'],
            ['name' => 'permissions menu', 'guard_name' => 'web'],
        ];

        foreach($permissions as $permission){
            Permission::create($permission);
        }

        $role = Role::create(['name' => 'free']);
        $role->givePermissionTo(Permission::all());
		$role = Role::create(['name' => 'expired']);

        $role = Role::create(['name' => 'UTY']);
		$role = Role::create(['name' => 'UTM']);

        $role = Role::create(['name' => 'TTY']);
        $role = Role::create(['name' => 'TTM']);

        $role = Role::create(['name' => 'OTY']);
        $role = Role::create(['name' => 'OTM']);

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        //Free Role
        $free = Role::where('name', 'free')->first();
        $role_id = $free->id;
        $free_permission = [
            'dashboard',
            'calendar',
            'files',
            'information',
            'sources',
            'people',
        ];
        foreach($free_permission as $link){
            $permission = Permission::where('name', $link)->first();
            if($permission !== null ) {
                $permission->roles()->detach($role_id);
                $permission->roles()->attach($role_id);
            }
        } 

        //Expired Role
        $expired = Role::where('name', 'expired')->first();
        $role_id = $expired->id;
        $expired_permissions = [
            'people',
            'family',
            'references',
            'trees',
        ];
        foreach($expired_permissions as $link){
            $permission = Permission::where('name', $link)->first();
            if($permission !== null ) {
                $permission->roles()->detach($role_id);
                $permission->roles()->attach($role_id);
            }
        }

        $roles = Role::whereNotIn('name', ['free', 'expired'])->get();
        $permissions = Permission::where([
            ['name','not like', '%information%'],
            ['name','not like', '%sources%'],
            ['name','not like', '%people%'],
        ])->get();
        foreach($roles as $role) {
            foreach($permissions as $permission){
                if($permission !== null ) {
                    $permission->roles()->detach($role->id);
                    $permission->roles()->attach($role->id);
                }
            }
        }
    }
}