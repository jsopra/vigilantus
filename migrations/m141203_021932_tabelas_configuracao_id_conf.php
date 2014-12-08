<?php

use yii\db\Migration;

class m141203_021932_tabelas_configuracao_id_conf extends Migration
{
    public function safeUp()
    {
        $this->addColumn('configuracoes_clientes', 'configuracao_id', 'integer references configuracoes(id)');
    }

    public function safeDown()
    {
        echo "m141203_021932_tabelas_configuracao_id_conf cannot be reverted.\n";
        return false;
    }
}
