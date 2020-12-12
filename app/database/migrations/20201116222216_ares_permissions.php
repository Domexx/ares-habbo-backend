<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class AresPermissions
 */
final class AresPermissions extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_permissions');

        $data = [
            ['name' => 'delete-article', "description" => 'Access to delete an Article', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'create-article', "description" => 'Access to create an Article', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit-article-comment', "description" => 'Access to edit an Article related comment', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete-article-comment', "description" => 'Access to delete an Article related comment', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete-guestbook-entry', "description" => 'Access to delete a Guestbook entry', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete-payment', "description" => 'Access to delete a Payment', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'set-global-setting', "description" => 'Access to set a global Setting', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete-photo', "description" => 'Access to delete a Photo', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete-forum-comment', "description" => 'Access to delete a Forum comment', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'create-forum-topic', "description" => 'Access to create a Forum topic', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit-forum-topic', "description" => 'Access to edit a Forum topic', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete-forum-topic', "description" => 'Access to delete a Forum topic', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete-forum-thread', "description" => 'Access to delete a Forum thread', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'list-all-roles', "description" => 'Access to list all Roles', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'create-role', "description" => 'Access to create a Role', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'create-child-role', "description" => 'Access to create a Child Role', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'assign-role', "description" => 'Access to assign a Role', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete-role', "description" => 'Access to delete a Role', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'list-all-permissions', "description" => 'Access to list all Permissions', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'create-permission', "description" => 'Access to create a Permission', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'create-role-permission', "description" => 'Access to create a Role Permission', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete-role-permissions', "description" => 'Access to delete a Role Permission', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'rcon-disconnect-user', "description" => 'Access to disconnect a User', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit-article', "description" => 'Access to edit an Article', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'update-currency', "description" => 'Access to update the currencies on a User', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'execute-rcon-command', "description" => 'Access to execute a rcon command through api', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
        ];

        $table->addColumn('name', 'string', ['limit' => 100])
            ->addColumn('description', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('status', 'integer', ['limit' => 11])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->insert($data)
            ->create();
    }
}
