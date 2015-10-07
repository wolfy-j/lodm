<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2015
 */
namespace Spiral\LODM\Commands;

use Illuminate\Console\Command;

/**
 * Performs ODM schema update.
 */
class Update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'odm:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update ODM behaviour schema.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

    }
}