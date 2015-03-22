<?php

namespace tests\factories;

use app\models\UsuarioRole;
use Phactory;

class UsuarioPhactory
{
    public function blueprint()
    {
        return [
            'nome' => 'Usuário #{sn}',
            'login' => 'login#{sn}',
            'sal' => 'salgado',
            'senha' => '12345678',
            'confirmacao_senha' => '12345678',
            'usuario_role_id' => UsuarioRole::USUARIO,
            'email' => 'email#{sn}@vigilantus.com.br',
            'cliente' => Phactory::hasOne('cliente'),
        ];
    }

    public function root()
    {
        return array(
            'usuario_role_id' => UsuarioRole::ROOT,
            'cliente' => null,
        );
    }

    public function administrador()
    {
        return array(
            'usuario_role_id' => UsuarioRole::ADMINISTRADOR,
        );
    }

    public function gerente()
    {
        return array(
            'usuario_role_id' => UsuarioRole::GERENTE,
        );
    }
}
