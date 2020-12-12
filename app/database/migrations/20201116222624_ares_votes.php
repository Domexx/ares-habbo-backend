<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class AresVotes
 */
final class AresVotes extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_votes');
        $table->addColumn('user_id', 'integer', ['limit' => 11])
            ->addColumn('entity_id', 'integer', ['limit' => 11])
            ->addColumn('vote_entity', 'integer', ['limit' => 11])
            ->addColumn('vote_type', 'integer', ['limit' => 11])
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'RESTRICT'])
            ->create();
    }
}
