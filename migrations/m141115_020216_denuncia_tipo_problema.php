<?php

use yii\db\Migration;

class m141115_020216_denuncia_tipo_problema extends Migration
{
    public function safeUp()
    {
    	$this->createTable('denuncia_tipos_problemas', [
    		'id' => 'pk',
    		'municipio_id' => 'integer not null references municipios(id)',
    		'nome' => 'varchar not null',
    		'ativo' => 'boolean not null default false',
    		'inserido_por' => 'integer not null references usuarios(id)',
    		'data_cadastro' => 'timestamp without time zone default now()',
    		'atualizado_por' => 'integer references usuarios(id)',
    		'data_atualizacao' => 'timestamp',
		]);

		$this->addColumn('denuncias', 'denuncia_tipo_problema_id', 'integer references denuncia_tipos_problemas(id)');
    }

    public function safeDown()
    {
        echo "m141115_020216_denuncia_tipo_problema cannot be reverted.\n";
        return false;
    }
}
