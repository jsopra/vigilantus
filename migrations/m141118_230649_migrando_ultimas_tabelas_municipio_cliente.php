<?php

use yii\db\Migration;

class m141118_230649_migrando_ultimas_tabelas_municipio_cliente extends Migration
{
    public function safeUp()
    {
    	$tabelas = [
            ['nome' => 'denuncias','excluiMunicipio' => true,],
            ['nome' => 'denuncia_tipos_problemas','excluiMunicipio' => true,],
            ['nome' => 'denuncia_historico','excluiMunicipio' => true,],
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
        echo "m141118_230649_migrando_ultimas_tabelas_municipio_cliente cannot be reverted.\n";
        return false;
    }
}
