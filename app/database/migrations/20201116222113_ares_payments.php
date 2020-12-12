<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class AresPayments
 */
final class AresPayments extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_payments');
        $table->addColumn('user_id', 'integer', ['limit' => 10])
            ->addColumn('code', 'string', ['limit' => 40])
            ->addColumn('processed', 'integer', ['limit' => 11])
            ->addColumn('type', 'integer', ['limit' => 11])
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'RESTRICT'])
            ->create();
    }
}
