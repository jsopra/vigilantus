<?php

use yii\db\Migration;

class m160423_130601_flag_recebe_email_ocorrencia_em_usuario extends Migration
{
    public function safeUp()
    {
        $this->addColumn('usuarios', 'recebe_email_ocorrencia', 'boolean DEFAULT FALSE');
    }

    public function safeDown()
    {
        $this->dropColumn('usuarios', 'recebe_email_ocorrencia');
    }
}
