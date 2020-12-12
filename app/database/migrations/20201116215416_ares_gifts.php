<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class AresGifts
 */
final class AresGifts extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_gifts');
        $table->addColumn('user_id', 'integer', ['limit' => 10])
            ->addColumn('amount', 'integer', ['limit' => 11])
            ->addColumn('pick_time', 'integer', ['limit' => 11])
            ->create();
    }
}
