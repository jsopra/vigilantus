<?php

use yii\db\Migration;

class m140604_112511_isolando_configuracoes_foco extends Migration
{
    public function safeUp()
    {
        $this->addColumn('especies_transmissores', 'qtde_metros_area_foco', 'integer not null default 300');
        $this->addColumn('especies_transmissores', 'qtde_dias_permanencia_foco', 'integer not null default 360');
    }

    public function safeDown()
    {
        echo "m140604_112511_isolando_configuracoes_foco cannot be reverted.\n";
        return false;
    }
}
