<?php

use app\models\Configuracao;
use app\models\ConfiguracaoTipo;
use yii\db\Migration;

class m150309_215532_configuracoes_denuncia extends Migration
{
    public function safeUp()
    {
        Configuracao::cria(
            2,
            'Qtde. dias faixa de tempo verde',
            'Quantidade de dias sem solução para pintar em verde a denúncia',
            ConfiguracaoTipo::TIPO_INTEIRO,
            '8'
        );

        Configuracao::cria(
            3,
            'Qtde. dias faixa de tempo vermelho',
            'Quantidade de dias sem solução para pintar em vermelho a denúncia',
            ConfiguracaoTipo::TIPO_INTEIRO,
            '15'
        );
    }

    public function safeDown()
    {
        echo "m150309_215532_configuracoes_denuncia cannot be reverted.\n";
        return false;
    }
}
