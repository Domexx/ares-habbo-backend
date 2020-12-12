<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class AresUoth
 */
final class AresUoth extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_uoth');
        $table->addColumn('user_id', 'integer', ['limit' => 11])
            ->addColumn('to_timestamp', 'integer', ['limit' => 11])
            ->addColumn('created_at', 'datetime')
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'RESTRICT'])
            ->create();
    }
}
