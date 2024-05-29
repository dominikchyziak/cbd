<?php

use Phinx\Migration\AbstractMigration;

class CreateProductPhotosTable extends AbstractMigration
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
        $this->table('duo_product_photos')
            ->addColumn('product_id', 'integer')
            ->addColumn('order', 'integer', array('null' => true))
            ->addColumn('name', 'string', array('limit' => 255, 'null' => true))
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', array('null' => true))
            ->addForeignKey('product_id', 'duo_products', 'id', array('delete' => 'CASCADE', 'update' => 'RESTRICT'))
            ->create();
    }
}
