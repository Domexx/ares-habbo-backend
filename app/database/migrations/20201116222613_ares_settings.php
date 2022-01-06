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
            ['key' => 'register_enabled', "value" => 'true'],
            ['key' => 'facebook_link', "value" => 'https://www.facebook.com/Habbo'],
            ['key' => 'twitter_link', "value" => 'https://twitter.com/habbo'],
            ['key' => 'instagram_link', "value" => 'https://www.instagram.com/habboofficial'],
            ['key' => 'client_enabled', "value" => '1'],
            ['key' => 'paypal_secret_id', "value" => 'YOUR_PAYPAL_SECRET_ID'],
            ['key' => 'paypal_client_id', "value" => 'YOUR_PAYPAL_CLIENT_ID'],
            ['key' => 'paypal_currency', "value" => 'USD'],
            ['key' => 'paypal_sandbox_enabled', "value" => '1'],
            ['key' => 'adsense_client_id', "value" => 'YOUR_ADSENSE_CLIENT_ID'],
        ];

        $table->addColumn('key', 'string', ['limit' => 50])
            ->addColumn('value', 'string', ['limit' => 70])
            ->insert($data)
            ->create();
    }
}
