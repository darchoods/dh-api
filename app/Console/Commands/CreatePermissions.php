<?php

namespace App\Console\Commands;

use App\Models\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CreatePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dh:create-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates Permissions from the permissions config file.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->createPermissions(config('permissions'));
    }

    private function createPermissions(array $permissions)
    {
        $this->info('[create-permissions] Running Permission Creation.');
        $permissionList = Permission::all();

        foreach ($permissions as $permissionName => $active) {
            $permission = $permissionList->where('name', $permissionName)->first();

            if ($permission) {
                $permission->active = $active;
                $permission->save();
            } else {
                $permission = new Permission();
                $permission->name = $permissionName;
                $permission->active = $active;
                $permission->save();
            }
        }
        $this->info(sprintf('[create-permissions] %d Permissions Created/Updated.', Permission::all()->count()));
    }
}
