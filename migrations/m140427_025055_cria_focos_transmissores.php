<?php

use yii\db\Migration;

class m140427_025055_cria_focos_transmissores extends Migration
{
    public function safeUp()
    {
        $this->createTable(
            'focos_transmissores',
            [
                'id' => 'pk',
                'inserido_por' => 'integer NOT NULL REFERENCES usuarios ON UPDATE cascade ON DELETE restrict',
                'atualizado_por' => 'integer REFERENCES usuarios ON UPDATE cascade ON DELETE restrict',
                'quarteirao_id' => 'integer NOT NULL REFERENCES bairro_quarteiroes ON UPDATE cascade ON DELETE restrict',
                'tipo_imovel_id' => 'integer NOT NULL REFERENCES imovel_tipos ON UPDATE cascade ON DELETE restrict',
                'tipo_deposito_id' => 'integer NOT NULL REFERENCES deposito_tipos ON UPDATE cascade ON DELETE restrict',
                'especie_transmissor_id' => 'integer NOT NULL REFERENCES especies_transmissores ON UPDATE cascade ON DELETE restrict',
                'data_cadastro' => 'timestamp with time zone NOT NULL DEFAULT now()',
                'data_atualizacao' => 'timestamp with time zone',
                'data_entrada' => 'date',
                'data_exame' => 'date',
                'data_coleta' => 'date',
                'endereco' => 'varchar(2048) NOT NULL',
                'quantidade_forma_aquatica' => 'integer NOT NULL DEFAULT 0',
                'quantidade_forma_adulta' => 'integer NOT NULL DEFAULT 0',
                'quantidade_ovos' => 'integer NOT NULL DEFAULT 0',
            ]
        );
    }

    public function safeDown()
    {
        $this->dropTable('focos_transmissores');
    }
}
