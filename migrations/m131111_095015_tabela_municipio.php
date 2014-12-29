<?php

use yii\db\Migration;

class m131111_095015_tabela_municipio extends Migration
{
    public function safeUp()
    {
        $this->createTable('municipios', array(
            'id' => 'pk',
            'nome' => 'varchar not null',
            'sigla_estado' => 'varchar(2) not null',
            'nome_contato' => 'varchar not null',
            'email_contato' => 'varchar',
            'telefone_contato' => 'varchar not null',
            'departamento' => 'varchar not null',
            'cargo' => 'varchar',
        ));

        $this->insert('municipios', array(
            'nome' => 'Chapecó',
            'sigla_estado' => 'SC',
            'nome_contato' => 'Junir Antonio Lutinski',
            'email_contato' => 'junir@unochapeco.edu.br',
            'telefone_contato' => '(49) 3319-1407',
            'departamento' => 'Vigilância em Saúde Ambiental',
            'cargo' => 'Coordenador',
        ));
    }

    public function safeDown()
    {
        $this->dropTable('municipios');
    }
}
