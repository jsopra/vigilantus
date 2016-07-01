<?php

use yii\db\Migration;

class m160622_184420_add_column_detalhes_publicos extends Migration
{
    public function safeUp()
    {
        $this->addColumn('ocorrencias', 'detalhes_publicos', 'varchar');
    }

    public function safeDown()
    {
        echo "m160622_184420_add_column_detalhes_publicos cannot be reverted.\n";
        return false;
    }
}
