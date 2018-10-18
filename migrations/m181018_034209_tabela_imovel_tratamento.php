<?php

use yii\db\Migration;

class m181018_034209_tabela_imovel_tratamento extends Migration
{
    public function safeUp()
    {
    	$this->dropColumn('visita_imoveis', 'focal_imovel_tratamento');
    	$this->dropColumn('visita_imoveis', 'focal_larvicida_tipo');
    	$this->dropColumn('visita_imoveis', 'focal_larvicida_qtde_gramas');
    	$this->dropColumn('visita_imoveis', 'focal_larvicida_qtde_dep_tratado');
    	$this->dropColumn('visita_imoveis', 'perifocal_adulticida_tipo');
    	$this->dropColumn('visita_imoveis', 'perifocal_adulticida_qtde_cargas');

    	$this->createTable('visita_imovel_tratamentos', [
            'id' => 'pk',
            'visita_id' => 'integer not null references visita_imoveis(id)',
            'focal_imovel_tratamento' => 'integer NOT NULL DEFAULT 0',
            'focal_larvicida_tipo' => 'integer', //1 - Pyriproxyfen ou 2 - Outros
       		'focal_larvicida_qtde_gramas' => 'float',
       		'focal_larvicida_qtde_dep_tratado' => 'integer NOT NULL DEFAULT 0',     
            'perifocal_adulticida_tipo' => 'integer', //1 - Pyriproxyfen ou 2 - Outros
       		'perifocal_adulticida_qtde_cargas' => 'float',
    	]);
    }

    public function safeDown()
    {
        $this->dropTable('visita_imovel_tratamentos');
    }
}
