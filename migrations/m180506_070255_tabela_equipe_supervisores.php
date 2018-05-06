<?php

use yii\db\Migration;

class m180506_070255_tabela_equipe_supervisores extends Migration
{
    public function safeUp()
    {
		$this->createTable('equipe_supervisores', [
            'id' => 'pk',
            'cliente_id' => 'integer not null references clientes(id)',
            'equipe_id' => 'integer not null references equipes(id)',
            'usuario_id' => 'integer not null references usuarios(id)',
            'codigo' => 'varchar',
        ]);
    }

    public function safeDown()
    {
        echo "m180506_070255_tabela_equipe_supervisores cannot be reverted.\n";
        return false;
    }
}
