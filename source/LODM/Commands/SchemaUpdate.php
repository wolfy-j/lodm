<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\LODM\Commands;

use Illuminate\Console\Command;
use Spiral\ODM\ODM;

/**
 * Performs ODM schema update.
 */
class SchemaUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'odm:schema';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update ODM behaviour schema and ensure needed database indexes';

    /**
     * @var ODM
     */
    protected $odm = null;

    /**
     * @param ODM $odm
     */
    public function __construct(ODM $odm)
    {
        $this->odm = $odm;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $builder = $this->odm->schemaBuilder(true);
        $this->odm->setSchema($builder, true);


            $builder->createIndexes();

    }
}
