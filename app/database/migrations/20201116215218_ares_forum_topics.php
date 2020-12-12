<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class AresForumTopics
 */
final class AresForumTopics extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_forum_topics');
        $table->addColumn('title', 'string', ['limit' => 100])
            ->addColumn('slug', 'string', ['limit' => 100])
            ->addColumn('description', 'string', ['limit' => 100])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->create();
    }
}
