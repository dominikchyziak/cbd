<?php

use Phinx\Migration\AbstractMigration;

class CreateCustomElementFieldsTable extends AbstractMigration
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
        $this->table('duo_custom_element_fields')
            ->addColumn('custom_element_id', 'integer')
            ->addColumn('lang', 'string', array('limit' => 2))
            ->addColumn('title', 'string', array('limit' => 255, 'null' => true))
            ->addColumn('type', 'string', array('limit' => 50, 'null' => true))
            ->addColumn('value', 'text', array('null' => true))
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', array('null' => true))
            ->addForeignKey('custom_element_id', 'duo_custom_elements', 'id', array('delete' => 'CASCADE', 'update' => 'RESTRICT'))
            ->create();
    }
}
