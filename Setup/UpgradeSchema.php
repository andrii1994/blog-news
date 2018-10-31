<?php
/**
 * Created by PhpStorm.
 * User: as
 * Date: 10/5/18
 * Time: 11:08 PM
 */

namespace Blog\News\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * Class UpgradeSchema
 * @package Blog\News\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{


    /**
     * Upgrades DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();
        if (version_compare($context->getVersion(), '0.0.2', '<')) {
            if (!$installer->tableExists('blog_news_store')) {
                /**
                 * Create table 'blog_news_store'
                 */
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('blog_news_store')
                )->addColumn(
                    'post_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false, 'primary' => true, 'unsigned' => true],
                    'Post ID'
                )->addColumn(
                    'store_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Store ID'
                )->addIndex(
                    $installer->getIdxName('blog_news_store', ['store_id']),
                    ['store_id']
                )->addForeignKey(
                    $installer->getFkName('blog_news_store', 'post_id', 'blog_news_post', 'post_id'),
                    'post_id',
                    $installer->getTable('blog_news_post'),
                    'post_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )->addForeignKey(
                    $installer->getFkName('blog_news_store', 'store_id', 'store', 'store_id'),
                    'store_id',
                    $installer->getTable('store'),
                    'store_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )->setComment(
                    'Blog News Post To Store Linkage Table'
                );
                $installer->getConnection()->createTable($table);
            } else {
                $installer->getConnection()->dropTable('blog_news_store');
            }
        }
    }
}
