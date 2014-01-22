<?php

use yii\db\Schema;

class m140120_221822_ficha_cadastro_rg extends \yii\db\Migration
{
	public function up()
	{
        $this->createTable('bairro_ruas', array(
            'id' => 'pk',
            'municipio_id' => 'integer not null references municipios(id)',
            'bairro_id' => 'integer not null references bairros(id)',
            'nome' => 'varchar not null',
        ));
        
        $this->createTable('bairro_rua_imoveis', array(
            'id' => 'pk',
            'municipio_id' => 'integer not null references municipios(id)',
            'bairro_rua_id' => 'integer not null references bairro_ruas(id)',
            'numero' => 'varchar',
            'sequencia' => 'varchar',
            'complemento' => 'varchar',
        ));
        
        $this->createTable('boletins_rg', array(
            'id' => 'pk',
            'municipio_id' => 'integer not null references municipios(id)',
            'data' => 'date not null',
            'folha' => 'integer not null',
            'ano' => 'integer not null',
            'bairro_id' => 'integer not null references bairros(id)',
            'quarteirao_numero' => 'integer',
            'seq' => 'varchar',
            'data_cadastro' => 'timestamp with time zone not null default now()',
            'inserido_por' => 'integer not null references usuarios(id)',
        ));
        
        $this->createTable('boletim_rg_imoveis', array(
            'id' => 'pk',
            'data' => 'date not null',
            'boletim_rg_id' => 'integer not null references boletins_rg(id)',
            'bairro_rua_imovel_id' => 'integer not null references bairro_rua_imoveis(id)',
            'condicao_imovel_id' => 'integer not null references imovel_condicoes(id)',
        ));
        
        $this->createTable('boletim_rg_fechamento', array(
            'id' => 'pk',
            'data' => 'date not null',
            'boletim_rg_id' => 'integer not null references boletins_rg(id)',
            'condicao_imovel_id' => 'integer not null references imovel_condicoes(id)',
            'quantidade' => 'integer not null default 0',
        ));
	}

	public function down()
	{
		echo "m140120_221822_ficha_cadastro_rg cannot be reverted.\n";
		return false;
	}
}
