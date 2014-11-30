<?php

use yii\db\Migration;

class m141111_095832_migrando_municipio_para_cliente extends Migration
{
    public function safeUp()
    {
    	$this->addColumn('clientes', 'nome_contato', 'varchar NOT NULL');
    	$this->addColumn('clientes', 'email_contato', 'varchar');
    	$this->addColumn('clientes', 'telefone_contato', 'varchar NOT NULL');
    	$this->addColumn('clientes', 'departamento', 'varchar NOT NULL');
    	$this->addColumn('clientes', 'cargo', 'varchar');
    	$this->addColumn('clientes', 'brasao', 'varchar');

		$this->execute("
            INSERT INTO clientes (municipio_id, data_cadastro, nome_contato, email_contato, telefone_contato, departamento, cargo) 
            SELECT id, current_timestamp, nome_contato, email_contato, telefone_contato, departamento, cargo FROM municipios
        ");

        $this->dropColumn('municipios', 'nome_contato');
    	$this->dropColumn('municipios', 'email_contato');
    	$this->dropColumn('municipios', 'telefone_contato');
    	$this->dropColumn('municipios', 'departamento');
    	$this->dropColumn('municipios', 'cargo');
    }

    public function safeDown()
    {
        echo "m141111_095832_migrando_municipio_para_client cannot be reverted.\n";
        return false;
    }
}
