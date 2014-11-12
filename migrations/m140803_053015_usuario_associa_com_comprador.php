<?php

use yii\db\Migration;

class m140803_053015_usuario_associa_com_comprador extends Migration
{
    public function safeUp()
    {
        $this->addColumn('usuarios', 'cliente_id', 'integer references clientes (id)');
        
        $this->execute("
            UPDATE usuarios 
            SET cliente_id = (
                SELECT id 
                FROM clientes
                WHERE clientes.municipio_id = usuarios.municipio_id 
            )
        ");
        
        $this->dropColumn('usuarios', 'municipio_id');
    }

    public function safeDown()
    {
        echo "m140803_053015_usuario_associa_com_comprador cannot be reverted.\n";
        return false;
    }
}
