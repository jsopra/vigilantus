<?php

use yii\db\Migration;

class m141120_091556_cliente_id_populado extends Migration
{
    public function safeUp()
    {
    	$tabelas = [
            'denuncias',
            'denuncia_tipos_problemas',
            'denuncia_historico',
            'bairro_categorias',
            'bairro_quarteiroes',
            'bairros',
            'boletim_rg_fechamento',
            'boletim_rg_imoveis',
            'boletins_rg',
            'deposito_tipos',
            'especies_transmissores',
            'focos_transmissores',
            'imoveis',
            'imovel_tipos',
            'ruas',
            ['usuarios', 'UPDATE usuarios SET cliente_id = 1 WHERE id <> 1']
        ];

        foreach($tabelas as $tabela) {
            
            if(!is_array($tabela)) {
                $this->execute("
                    UPDATE " . $tabela . " 
                    SET cliente_id = 1
                ");
            }
            else {
                $this->execute($tabela[1]);
            }
        }
    }

    public function safeDown()
    {
        echo "m141120_091556_cliente_id_populado cannot be reverted.\n";
        return false;
    }
}
