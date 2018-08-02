<?php

use yii\db\Migration;

class m180716_143808_visita_agente extends Migration
{
    public function safeUp()
    {
    	$this->createTable('semana_epidemiologica_visitas', [
            'id' => 'pk',
            'semana_epidemiologica_id' => 'integer not null references semanas_epidemiologicas(id)',
            'bairro_id' => 'integer not null references bairros(id)',
            'quarteirao_id' => 'integer not null references bairro_quarteiroes(id)',
            'agente_id' => 'integer not null references equipe_agentes(id)',  
            'cliente_id' => 'integer not null references clientes(id)',
            'inserido_por' => 'integer not null references usuarios(id)',
            'data_cadastro' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'atualizado_por' => 'integer references usuarios(id)',
            'data_atualizacao' => 'timestamp with time zone',

            'visita_status_id' => 'integer', //CONCLUIDA S ou N
            'data_atividade' => 'date',
    	]);

    	$this->createTable('visita_imoveis', [
            'id' => 'pk',
            'cliente_id' => 'integer not null references clientes(id)',
            'inserido_por' => 'integer not null references usuarios(id)',
            'data_cadastro' => 'timestamp with time zone NOT NULL DEFAULT now()',
            'atualizado_por' => 'integer references usuarios(id)',
            'data_atualizacao' => 'timestamp with time zone',

            'semana_epidemiologica_visita_id' => 'integer not null references semana_epidemiologica_visitas(id)',

            'visita_atividade_id' => 'integer',

            //dados gerais
            'rua_id' => 'integer references ruas(id)',
            'quarteirao_id' => 'integer not null references bairro_quarteiroes(id)',
            'logradouro' => 'text',
            'numero' => 'varchar',
            'sequencia' => 'varchar',
            'complemento' => 'varchar',
            'tipo_imovel_id' => 'integer references imovel_tipos(id)',
            'hora_entrada' => 'time',
            'visita_tipo' => 'integer not null', //normal ou recuperada
            'pendencia' => 'integer', //fechada ou recusada
            'depositos_elimidados' => 'integer NOT NULL DEFAULT 0',

            //coleta da amostra
            'numero_amostra_inicial' => 'varchar',
            'numero_amostra_final' => 'varchar',
            'quantidade_tubitos' => 'integer NOT NULL DEFAULT 0',

            //tratamento
            'focal_imovel_tratamento' => 'integer NOT NULL DEFAULT 0',
            'focal_larvicida_tipo' => 'integer', //1 - Pyriproxyfen ou 2 - Outros
       		'focal_larvicida_qtde_gramas' => 'float',
       		'focal_larvicida_qtde_dep_tratado' => 'integer NOT NULL DEFAULT 0',     
            'perifocal_adulticida_tipo' => 'integer', //1 - Pyriproxyfen ou 2 - Outros
       		'perifocal_adulticida_qtde_cargas' => 'float',
    	]);

    	$this->createTable('visita_imovel_depositos', [
            'id' => 'pk',
            'visita_id' => 'integer not null references visita_imoveis(id)',
            'tipo_deposito_id' => 'integer not null references deposito_tipos(id)',
            'quantidade' => 'integer',
    	]);
    }

    public function safeDown()
    {
        $this->dropTable('semana_epidemiologica_visitas');
        $this->dropTable('visita_imoveis');
        $this->dropTable('visita_imovel_depositos');
    }
}