<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class AresRconCommands
 */
final class AresRconCommands extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_rcon_commands');

        $data = [
            ['command' => 'alertuser', "title" => 'Useralert', 'description' => 'Alerts an User in the Hotel'],
            ['command' => 'disconnect', "title" => 'Disconnect', 'description' => 'Disconnects an User in the Hotel'],
            ['command' => 'forwarduser', "title" => 'Forwarduser', 'description' => 'Forwards an User'],
            ['command' => 'givebadge', "title" => 'Give Badge', 'description' => 'Give a Badge to an User'],
            ['command' => 'givecredits', "title" => 'Give Credits', 'description' => 'Give Credits to an User'],
            ['command' => 'givepixels', "title" => 'Give Pixels', 'description' => 'Give Pixels to an User'],
            ['command' => 'givepoints', "title" => 'Give Points', 'description' => 'Give Points to an User'],
            ['command' => 'hotelalert', "title" => 'Alerts the Hotel', 'description' => 'Send an Hotelalert'],
            ['command' => 'sendgift', "title" => 'Send a Gift', 'description' => 'Sends a Gift to a specific User'],
            ['command' => 'sendroombundle', "title" => 'Send a Roombundle', 'description' => 'Sends a Roombundle to a specific User'],
            ['command' => 'setrank', "title" => 'Set Rank', 'description' => 'Sets the Rank of am User'],
            ['command' => 'updatewordfilter', "title" => 'Update Wordfilter', 'description' => 'Updates the Wordfilter'],
            ['command' => 'updatecatalog', "title" => 'Update Catalog', 'description' => 'Updates the Catalog'],
            ['command' => 'executecommand', "title" => 'Execute Command', 'description' => 'Executes a Command'],
            ['command' => 'progressachievement', "title" => 'Progressachievement', 'description' => 'Progress'],
            ['command' => 'updateuser', "title" => 'Update User', 'description' => 'Updates an User'],
            ['command' => 'friendrequest', "title" => 'Friendrequest', 'description' => 'Sends a Friendrequest'],
            ['command' => 'imagehotelalert', "title" => 'Imagehotelalert', 'description' => 'Sends an Hotelalert with an Image'],
            ['command' => 'imagealertuser', "title" => 'Imagealertuser', 'description' => 'Sends an Alert to an User with an Image'],
            ['command' => 'stalkuser', "title" => 'Stalkuser', 'description' => 'Stalk a User'],
            ['command' => 'staffalert', "title" => 'Staffalert', 'description' => 'Send a Staffalert'],
            ['command' => 'modticket', "title" => 'Modticket', 'description' => 'Creates a Modtoolticket'],
            ['command' => 'talkuser', "title" => 'Talkuser', 'description' => 'Talk to an User'],
            ['command' => 'changeroomowner', "title" => 'Change Roomowner', 'description' => 'Change the Owner of a Room'],
            ['command' => 'muteuser', "title" => 'Muteuser', 'description' => 'Mute an User'],
            ['command' => 'giverespect', "title" => 'Give Respect', 'description' => 'Give Respect to an User'],
            ['command' => 'ignoreuser', "title" => 'Ignore User', 'description' => 'Ignore an User'],
            ['command' => 'setmotto', "title" => 'Set Motto', 'description' => 'Sets the Motto of an User'],
            ['command' => 'giveuserclothing', "title" => 'Give Userclothing', 'description' => 'Give Clothing to an User'],
        ];


        $table->addColumn('command', 'string', ['limit' => 100])
            ->addColumn('description', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('title', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('permission_id', 'integer', ['limit' => 11, 'null' => true])
            ->insert($data)
            ->create();
    }
}
