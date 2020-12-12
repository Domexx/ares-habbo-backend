<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class AresArticles
 */
final class AresArticles extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_articles');
        $table->addColumn('title', 'string', ['limit' => 100])
            ->addColumn('slug', 'string', ['limit' => 100])
            ->addColumn('description', 'text')
            ->addColumn('content', 'text')
            ->addColumn('image', 'text')
            ->addColumn('author_id', 'integer', ['limit' => 11])
            ->addColumn('hidden', 'integer', ['limit' => 11])
            ->addColumn('pinned', 'integer', ['limit' => 11])
            ->addColumn('likes', 'integer', ['limit' => 11])
            ->addColumn('dislikes', 'integer', ['limit' => 11])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addForeignKey('author_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->create();
    }
}
