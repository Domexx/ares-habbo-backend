<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class AresRolesUser
 */
final class AresRolesUser extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_roles_user');
        $table->addColumn('role_id', 'integer', ['limit' => 10])
            ->addColumn('user_id', 'integer', ['limit' => 10])
            ->addColumn('created_at', 'datetime')
            ->addForeignKey('role_id', 'ares_roles', 'id', ['delete'=> 'CASCADE', 'update'=> 'RESTRICT'])
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'RESTRICT'])
            ->create();
    }
}
