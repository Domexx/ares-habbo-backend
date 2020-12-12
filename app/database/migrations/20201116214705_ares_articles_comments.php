<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class AresArticlesComments
 */
final class AresArticlesComments extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_articles_comments');
        $table->addColumn('user_id', 'integer', ['limit' => 11])
            ->addColumn('article_id', 'integer', ['limit' => 11])
            ->addColumn('content', 'text')
            ->addColumn('is_edited', 'integer', ['limit' => 11])
            ->addColumn('likes', 'integer', ['limit' => 11])
            ->addColumn('dislikes', 'integer', ['limit' => 11])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'RESTRICT'])
            ->addForeignKey('article_id', 'ares_articles', 'id', ['delete'=> 'CASCADE', 'update'=> 'RESTRICT'])
            ->create();
    }
}
