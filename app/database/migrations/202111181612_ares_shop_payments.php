<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Class AresArticles
 */
final class AresShopPayments extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('ares_shop_payments');
        $table->addColumn('user_id', 'integer', ['limit' => 11])
            ->addColumn('offer_id', 'integer', ['limit' => 11])
            ->addColumn('order_id', 'string', ['limit' => 255])
            ->addColumn('payer_id', 'string', ['limit' => 255])
            ->addColumn('status', 'string', ['limit' => 20])
            ->addColumn('delivered', 'string', ['limit' => 5])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->create();
    }
}
