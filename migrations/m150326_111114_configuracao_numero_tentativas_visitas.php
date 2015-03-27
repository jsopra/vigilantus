<?php

use app\models\Configuracao;
use app\models\ConfiguracaoTipo;
use yii\db\Migration;

class m150326_111114_configuracao_numero_tentativas_visitas extends Migration
{
    public function safeUp()
    {
        Configuracao::cria(
            4,
            'Qtde. tentativas visitação antes de fechar denúncia',
            'Quantidade de tentativas de visitação antes de fechar uma denúncia',
            ConfiguracaoTipo::TIPO_INTEIRO,
            '3'
        );
    }

    public function safeDown()
    {
        echo "m150326_111114_configuracao_numero_tentativas_visitas cannot be reverted.\n";
        return false;
    }
}
