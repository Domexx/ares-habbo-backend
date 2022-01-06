<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class AresArticles
 */
final class AresShopOffers extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_shop_offers');
        $table->addColumn('price', 'string', ['limit' => 10])
            ->addColumn('title', 'string', ['limit' => 255])
            ->addColumn('description', 'text')
            ->addColumn('image', 'string', ['limit' => 255])
            ->addColumn('data', 'text')
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->create();
    }
}
