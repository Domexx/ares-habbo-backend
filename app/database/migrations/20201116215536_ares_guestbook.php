<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class AresGuestbook
 */
final class AresGuestbook extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_guestbook');
        $table->addColumn('user_id', 'integer', ['limit' => 10])
            ->addColumn('profile_id', 'integer', ['limit' => 11, 'null' => true])
            ->addColumn('guild_id', 'integer', ['limit' => 11, 'null' => true])
            ->addColumn('content', 'text')
            ->addColumn('likes', 'integer', ['limit' => 11])
            ->addColumn('dislikes', 'integer', ['limit' => 11])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addForeignKey('guild_id', 'guilds', 'id', ['delete'=> 'CASCADE', 'update'=> 'RESTRICT'])
            ->addForeignKey('profile_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'RESTRICT'])
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'RESTRICT'])
            ->create();
    }
}
