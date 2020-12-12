<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class AresSettings
 */
final class AresSettings extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_settings');

        $data = [
            ['key' => 'maintenance', "value" => '0'],
            ['key' => 'discord_invite', "value" => 'https://discord.com/invite/pN7ZMFw'],
        ];

        $table->addColumn('key', 'string', ['limit' => 50])
            ->addColumn('value', 'string', ['limit' => 70])
            ->insert($data)
            ->create();
    }
}
