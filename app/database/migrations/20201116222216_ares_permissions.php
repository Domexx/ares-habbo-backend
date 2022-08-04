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
            ['name' => 'access-acp', "description" => 'Access to admin control panel', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],

            ['name' => 'view-articles', "description" => 'Access to view all articles', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete-article', "description" => 'Access to delete an article', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'create-article', "description" => 'Access to create an article', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit-article', "description" => 'Access to edit an article', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            
            ['name' => 'edit-article-comment', "description" => 'Access to edit an article related comment', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete-article-comment', "description" => 'Access to delete an article related comment', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            
            ['name' => 'view-users', "description" => 'Access to view all users', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            
            ['name' => 'delete-guestbook-entry', "description" => 'Access to delete a guestbook entry', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete-payment', "description" => 'Access to delete a payment', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            
            ['name' => 'view-web-settings', "description" => 'Access to view web settings', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            
            ['name' => 'delete-photo', "description" => 'Access to delete a photo', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete-forum-comment', "description" => 'Access to delete a forum comment', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'create-forum-topic', "description" => 'Access to create a forum topic', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit-forum-topic', "description" => 'Access to edit a forum topic', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete-forum-topic', "description" => 'Access to delete a forum topic', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete-forum-thread', "description" => 'Access to delete a forum thread', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            
            ['name' => 'view-roles', "description" => 'Access to view roles', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'create-role', "description" => 'Access to create a role', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            
            ['name' => 'create-child-role', "description" => 'Access to create a child role', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'assign-role', "description" => 'Access to assign a role', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete-role', "description" => 'Access to delete a role', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            
            ['name' => 'view-ranks', "description" => 'Access to view ranks', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'create-rank', "description" => 'Access to create a permission', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            
            ['name' => 'create-role-permission', "description" => 'Access to create a role Permission', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete-role-permissions', "description" => 'Access to delete a role Permission', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            
            ['name' => 'rcon-disconnect-user', "description" => 'Access to disconnect a user', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'update-currency', "description" => 'Access to update the currencies on a user', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'execute-rcon-command', "description" => 'Access to execute a rcon command through api', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
           
            ['name' => 'view-offers', "description" => 'Access to view offers', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'create-offer', "description" => 'Access to create an offer', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'edit-offer', "description" => 'Access to edit an offer', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'delete-offer', "description" => 'Access to delete an offer', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            
            ['name' => 'override-ads', "description" => 'Override advertisements', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'hide-leaderboard', "description" => 'Hide from leaderboards', 'status' => 1, 'created_at' => date('Y-m-d H:i:s')]
        
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
