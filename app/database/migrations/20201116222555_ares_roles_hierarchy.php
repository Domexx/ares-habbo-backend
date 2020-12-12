<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class AresRolesHierarchy
 */
final class AresRolesHierarchy extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_roles_hierarchy');
        $table->addColumn('parent_role_id', 'integer', ['limit' => 10])
            ->addColumn('child_role_id', 'integer', ['limit' => 10])
            ->addColumn('created_at', 'datetime')
            ->addForeignKey('parent_role_id', 'ares_roles', 'id', ['delete'=> 'CASCADE', 'update'=> 'RESTRICT'])
            ->addForeignKey('child_role_id', 'ares_roles', 'id', ['delete'=> 'CASCADE', 'update'=> 'RESTRICT'])
            ->create();
    }
}
