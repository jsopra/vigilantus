<?php

namespace tests\unit\models;

use app\models\Usuario;
use app\models\UsuarioRole;
use Phactory;
use tests\TestCase;

class UsuarioRoleTest extends TestCase
{
    public function testRolesCadastradas()
    {
        $this->assertEquals(4, UsuarioRole::find()->count());
        
        $this->assertEquals('Root', UsuarioRole::findOne(UsuarioRole::ROOT)->nome);
        $this->assertEquals('Administrador', UsuarioRole::findOne(UsuarioRole::ADMINISTRADOR)->nome);
        $this->assertEquals('Gerente', UsuarioRole::findOne(UsuarioRole::GERENTE)->nome);
        $this->assertEquals('Usuário', UsuarioRole::findOne(UsuarioRole::USUARIO)->nome);
    }
    
    public function testScopes()
    {
        $usuarioRoot = Phactory::usuario('root');
        $usuarioAdministrador = Phactory::usuario('administrador');
        
        $this->assertEquals(4, UsuarioRole::find()->doNivelDoUsuario($usuarioRoot)->count());
        $this->assertEquals(3, UsuarioRole::find()->doNivelDoUsuario($usuarioAdministrador)->count());
    }
    
    public function testDelete()
    {
        $role = UsuarioRole::findOne(UsuarioRole::ROOT);
        $this->setExpectedException('Exception');
        $role->delete();
    }
}
