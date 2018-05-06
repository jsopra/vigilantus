<?php

use yii\db\Migration;

class m180506_061904_role_supervisor extends Migration
{
    public function safeUp()
    {
        $this->insert('usuario_roles', array('id' => 7, 'nome' => 'Supervisor'));
    }

    public function safeDown()
    {
        echo "m180506_061904_role_supervisor cannot be reverted.\n";
        return false;
    }
}
