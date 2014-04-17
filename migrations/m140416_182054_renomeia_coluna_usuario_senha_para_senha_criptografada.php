<?php

use yii\db\Schema;

class m140416_182054_renomeia_coluna_usuario_senha_para_senha_criptografada extends \yii\db\Migration
{
    public function up()
    {
        $this->renameColumn('usuarios', 'senha', 'senha_criptografada');
    }

    public function down()
    {
        $this->renameColumn('usuarios', 'senha_criptografada', 'senha');
    }
}
