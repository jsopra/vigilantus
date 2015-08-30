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
        return [
            'usuario_role_id' => UsuarioRole::ROOT,
        ];
    }

    public function administrador()
    {
        return [
            'usuario_role_id' => UsuarioRole::ADMINISTRADOR,
        ];
    }

    public function gerente()
    {
        return [
            'usuario_role_id' => UsuarioRole::GERENTE,
        ];
    }
}
