<?php

namespace Holoultek\Capabilities\Commands;

use Holoultek\Capabilities\Models\Capability;
use Illuminate\Console\Command;

class GenerateCapabilitiesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:capabilities {--delete : Force deleting missing capabilities}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all capabilities';

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
        $created = 0;
        $changed = 0;
        $not_changed = 0;
        $deleted = 0;
        $capabilities = [];

        foreach (config('capabilities') as $controller => $capability) {
            foreach ($capability as $capability_name => $methods) {
                $_capability = Capability::updateOrCreate([
                    'name' => $capability_name,
                ], [
                    'controller' => $controller,
                    'methods' => $methods,
                ]);

                $capabilities[] = $_capability->id;

                if (!$_capability->wasRecentlyCreated && $_capability->wasChanged()) {
                    $changed++;
                }

                if (!$_capability->wasRecentlyCreated && !$_capability->wasChanged()) {
                    $not_changed++;
                }

                if ($_capability->wasRecentlyCreated) {
                    $created++;
                }
            }
        }

        if ($this->option('delete') || $this->confirm('Do you want to delete missing capabilities in the config file?', false)) {
            Capability::whereNotIn('id', $capabilities)->delete();
        }

        $this->table(
            ['All Capabilities', 'Created', 'Updated', 'Not Changed', 'Deleted'],
            [[Capability::all()->count(), $created, $changed, $not_changed, $deleted]],
        );

        return Command::SUCCESS;
    }
}
