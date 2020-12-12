<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class AresForumComments
 */
final class AresForumComments extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_forum_comments');
        $table->addColumn('thread_id', 'integer', ['limit' => 11])
            ->addColumn('topic_id', 'integer', ['limit' => 11])
            ->addColumn('user_id', 'integer', ['limit' => 11])
            ->addColumn('content', 'text')
            ->addColumn('is_edited', 'integer', ['limit' => 11])
            ->addColumn('likes', 'integer', ['limit' => 11])
            ->addColumn('dislikes', 'integer', ['limit' => 11])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->create();
    }
}
