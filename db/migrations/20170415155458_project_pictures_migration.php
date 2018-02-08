<?php

use Phinx\Migration\AbstractMigration;

class ProjectPicturesMigration extends AbstractMigration
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
            $table = $this->table('project_pics');
        $table->addColumn('project_id', 'integer', ['null' => true])
              ->addForeignKey('project_id', 'projects', 'id', array('delete'=> 'CASCADE', 'update'=> 'NO_ACTION'))
              ->addColumn('url', 'text')
              ->addColumn('type', 'enum', ['values' => ['normal', 'thumb']])
              ->addColumn('data_title', 'text', ['null' => true])
              ->addColumn('data_light_box', 'text', ['null' => true])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->create();
    }
}
