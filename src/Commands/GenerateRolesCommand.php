<?php

namespace Holoultek\Capabilities\Commands;

use Holoultek\Capabilities\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateRolesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh all roles capabilities from config file.';

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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach (config('roles') as $role_name => $capabilities) {
            $role = Role::updateOrCreate([
                'name' => $role_name
            ]);

            foreach ($capabilities as $capability_name) {
                $role->capabilityAttach($capability_name);
            }
        }

        return Command::SUCCESS;
    }
}
