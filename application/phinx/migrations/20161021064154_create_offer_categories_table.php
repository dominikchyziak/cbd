<?php

use Phinx\Migration\AbstractMigration;

class CreateOfferCategoriesTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->table('duo_offer_categories')
            ->addColumn('parent_id', 'integer', array('null' => true))
            ->addColumn('order', 'integer', array('null' => true))
            ->addColumn('image', 'string', array('limit' => 255, 'null' => true))
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', array('null' => true))
            ->addForeignKey('parent_id', 'duo_offer_categories', 'id', array('delete' => 'CASCADE', 'update' => 'RESTRICT'))
            ->create();
    }
}
