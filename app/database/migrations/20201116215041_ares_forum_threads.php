<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class AresForumThreads
 */
final class AresForumThreads extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_forum_threads');
        $table->addColumn('topic_id', 'integer', ['limit' => 11])
            ->addColumn('user_id', 'integer', ['limit' => 11])
            ->addColumn('slug', 'string', ['limit' => 100])
            ->addColumn('title', 'string', ['limit' => 100])
            ->addColumn('description', 'string', ['limit' => 100])
            ->addColumn('content', 'text')
            ->addColumn('likes', 'integer', ['limit' => 11])
            ->addColumn('dislikes', 'integer', ['limit' => 11])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->create();
    }
}
