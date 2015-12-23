<?php

use yii\db\Migration;

class m151208_175306_adicionar_usuario_tecnico_laboratorial extends Migration
{
    public function safeUp()
    {
        $this->insert('usuario_roles', array('id' => 6, 'nome' => 'Técnico Laboratorial'));
    }

    public function safeDown()
    {
        echo "m151208_175306_adicionar_usuario_tecnico_laboratorial cannot be reverted.\n";
        return false;
    }
}
