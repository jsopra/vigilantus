<?php

use yii\db\Migration;

class m150621_220831_renomeando_modulo_database extends Migration
{
    public function safeUp()
    {
        $this->execute("UPDATE modulos SET nome='Ocorrências' WHERE id = 1");
    }

    public function safeDown()
    {
        echo "m150621_220831_renomeando_modulo_database cannot be reverted.\n";
        return false;
    }
}
