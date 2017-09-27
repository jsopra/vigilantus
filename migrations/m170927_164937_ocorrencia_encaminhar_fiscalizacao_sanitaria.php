<?php

use yii\db\Migration;

class m170927_164937_ocorrencia_encaminhar_fiscalizacao_sanitaria extends Migration
{
    public function safeUp()
    {
    	$this->execute("UPDATE ocorrencias SET setor_id = 1 WHERE status = 9");
        $this->execute("UPDATE ocorrencias SET status = 10 WHERE status = 9");
    }

    public function safeDown()
    {
        echo "m170927_164937_ocorrencia_encaminhar_fiscalizacao_sanitaria cannot be reverted.\n";
        return false;
    }
}
