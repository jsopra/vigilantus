<?php

use yii\db\Migration;

class m131111_095524_tabela_usuario extends Migration
{
    public function safeUp()
    {
        $this->createTable('usuario_roles', array(
            'id' => 'pk',
            'nome' => 'varchar not null',
        ));

        $this->insert('usuario_roles', array('nome' => 'Root'));
        $this->insert('usuario_roles', array('nome' => 'Administrador'));
        $this->insert('usuario_roles', array('nome' => 'Gerente'));
        $this->insert('usuario_roles', array('nome' => 'Usuário'));

        $this->createTable('usuarios', array(
            'id' => 'pk',
            'nome' => 'varchar not null',
            'login' => 'varchar not null',
            'senha' => 'varchar not null',
            'sal' => 'varchar not null',
            'municipio_id' => 'integer references municipios(id)',
            'usuario_role_id' => 'integer not null references usuario_roles(id)',
            'ultimo_login' => 'timestamp with time zone',
            'email' => 'varchar not null',
            'token_recupera_senha' => 'varchar',
            'data_recupera_senha' => 'timestamp with time zone',
            'excluido' => 'boolean not null DEFAULT false',
        ));

        $sal = 'asd7y%i3';

        $this->insert('usuarios', array(
            'nome' => 'perspectiva.in',
            'login' => 'perspectiva',
            'senha' => md5('a1s2d3f4g5' . $sal),
            'sal' => $sal,
            'usuario_role_id' => 1,
            'email' => 'dengue@perspectiva.in',
        ));
    }

    public function safeDown()
    {
        $this->dropTable('usuarios');
        $this->dropTable('usuario_roles');
    }
}
