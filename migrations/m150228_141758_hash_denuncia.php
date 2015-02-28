<?php

use yii\db\Migration;

class m150228_141758_hash_denuncia extends Migration
{
    public function safeUp()
    {
        $this->addColumn('denuncias', 'hash_acesso_publico', 'varchar');
    }

    public function safeDown()
    {
        echo "m150228_141758_hash_denuncia cannot be reverted.\n";
        return false;
    }
}
