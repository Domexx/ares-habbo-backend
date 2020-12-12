<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class AresRolesPermission
 */
final class AresRolesPermission extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_roles_permission');

        $data = [
            ['role_id' => '1', "permission_id" => '1', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '2', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '3', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '4', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '5', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '6', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '7', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '8', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '9', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '10', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '11', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '12', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '13', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '14', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '15', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '16', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '17', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '18', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '19', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '20', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '21', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '22', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '23', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '24', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '25', 'created_at' => date('Y-m-d H:i:s')],
            ['role_id' => '1', "permission_id" => '26', 'created_at' => date('Y-m-d H:i:s')],
        ];

        $table->addColumn('role_id', 'integer', ['limit' => 10])
            ->addColumn('permission_id', 'integer', ['limit' => 10])
            ->addColumn('created_at', 'datetime')
            ->addForeignKey('role_id', 'ares_roles', 'id', ['delete'=> 'CASCADE', 'update'=> 'RESTRICT'])
            ->addForeignKey('permission_id', 'ares_permissions', 'id', ['delete'=> 'CASCADE', 'update'=> 'RESTRICT'])
            ->insert($data)
            ->create();
    }
}
