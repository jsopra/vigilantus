<?php
use app\models\UsuarioRole;

class UsuarioPhactory
{
    public function blueprint()
    {
        return [
            'nome' => 'Usuário #{sn}',
            'login' => 'login#{sn}',
            'sal' => 'salgado',
            'senha' => '12345678',
            'usuario_role_id' => UsuarioRole::USUARIO,
            'email' => 'email#{sn}@vigilantus.com.br',
            'cliente_id' => Phactory::hasOne('cliente'),
        ];
    }

    public function root()
    {
        return array(
            'usuario_role_id' => UsuarioRole::ROOT,
            'cliente_id' => null,
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
