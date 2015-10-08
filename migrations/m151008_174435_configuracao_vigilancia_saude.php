<?php

use app\models\Configuracao;
use app\models\ConfiguracaoTipo;
use yii\db\Migration;

class m151008_174435_configuracao_vigilancia_saude extends Migration
{
    public function safeUp()
    {
        Configuracao::cria(
            5,
            'Setor que utiliza ferramenta',
            'Nome do setor que faz uso da ferramenta de ocorrência',
            ConfiguracaoTipo::TIPO_STRING,
            'Vigilância em Saúde'
        );
    }

    public function safeDown()
    {
        echo "m151008_174435_configuracao_vigilancia_saude cannot be reverted.\n";
        return false;
    }
}
