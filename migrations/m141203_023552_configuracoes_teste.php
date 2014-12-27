<?php

use app\models\Configuracao;
use app\models\ConfiguracaoTipo;
use yii\db\Migration;

class m141203_023552_configuracoes_teste extends Migration
{
    public function safeUp()
    {
        Configuracao::cria(
            1,
            'Qtde. dias foco público',
            'Quantidade de dias que um foco fica visível de forma pública (Min: 60 dias)',
            ConfiguracaoTipo::TIPO_INTEIRO,
            '60'
        );
    }

    public function safeDown()
    {
        echo "m141203_023552_configuracoes_teste cannot be reverted.\n";
        return false;
    }
}
