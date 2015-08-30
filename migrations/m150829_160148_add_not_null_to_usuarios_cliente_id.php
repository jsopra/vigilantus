<?php

use yii\db\Expression;
use yii\db\Migration;

class m150829_160148_add_not_null_to_usuarios_cliente_id extends Migration
{
    public function safeUp()
    {
        $this->update(
            'usuarios',
            ['cliente_id' => new Expression("(select id from clientes order by id limit 1)")],
            'cliente_id IS NULL'
        );
        $this->execute("ALTER TABLE usuarios ALTER COLUMN cliente_id SET NOT NULL");
    }

    public function safeDown()
    {
        echo "m150829_160148_add_not_null_to_usuarios_cliente_id cannot be reverted.\n";
        return false;
    }
}
