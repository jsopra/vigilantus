<?php

use yii\db\Migration;

class m140803_142500_movendo_municipio_para_cliente extends Migration
{
    public function safeUp()
    {
        $tabelas = [
            ['nome' => 'bairro_categorias','excluiMunicipio' => true,],
            ['nome' => 'bairro_quarteiroes','excluiMunicipio' => false,],
            ['nome' => 'bairros','excluiMunicipio' => false,],
            ['nome' => 'boletim_rg_fechamento','excluiMunicipio' => true,],
            ['nome' => 'boletim_rg_imoveis','excluiMunicipio' => true,],
            ['nome' => 'boletins_rg','excluiMunicipio' => true,],
            ['nome' => 'deposito_tipos','excluiMunicipio' => true,],
            ['nome' => 'especies_transmissores','excluiMunicipio' => true,],
            ['nome' => 'focos_transmissores', 'excluiMunicipio' => false, 'customUpdate' => "
                UPDATE focos_transmissores SET cliente_id = (
                    SELECT cliente_id 
                    FROM bairro_quarteiroes
                    WHERE bairro_quarteiroes.id = focos_transmissores.bairro_quarteirao_id
                )
            "],
            ['nome' => 'imoveis','excluiMunicipio' => false,],
            ['nome' => 'imovel_tipos', 'excluiMunicipio' => true,],
            ['nome' => 'ruas','excluiMunicipio' => false,],
        ];
        
        foreach($tabelas as $tabela) {
            
            $this->addColumn($tabela['nome'], 'cliente_id', 'integer references clientes (id)');

            if(!isset($tabela['customUpdate'])) {
                $this->execute("
                    UPDATE " . $tabela['nome'] . " 
                    SET cliente_id = (
                        SELECT id 
                        FROM clientes
                        WHERE clientes.municipio_id = " . $tabela['nome'] . ".municipio_id 
                    )
                ");
            }
            else {
                $this->execute($tabela['customUpdate']);
            }

            if($tabela['excluiMunicipio']) {
                
                $this->dropColumn($tabela['nome'], 'municipio_id');
            }
        }
    }

    public function safeDown()
    {
        echo "m140803_142500_movendo_municipio_para_cliente cannot be reverted.\n";
        return false;
    }
}
