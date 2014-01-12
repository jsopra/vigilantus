<?php

namespace tests\unit\models;

use app\models\Usuario;
use app\models\UsuarioRole;
use yii\codeception\TestCase;

class UsuarioRoleTest extends TestCase
{
    public function testRolesCadastradas()
    {
        $this->assertEquals(4, UsuarioRole::find()->count());
        
        $this->assertEquals('Root', UsuarioRole::find(UsuarioRole::ROOT)->nome);
        $this->assertEquals('Administrador', UsuarioRole::find(UsuarioRole::ADMINISTRADOR)->nome);
        $this->assertEquals('Gerente', UsuarioRole::find(UsuarioRole::GERENTE)->nome);
        $this->assertEquals('Usuário', UsuarioRole::find(UsuarioRole::USUARIO)->nome);
    }
    
    public function testScopes()
    {
        $usuarioRoot = Usuario::find(1);
        $usuarioAdministrador = Usuario::find(2);
        
        $this->assertEquals(4, UsuarioRole::find()->doNivelDoUsuario($usuarioRoot)->count());
        $this->assertEquals(3, UsuarioRole::find()->doNivelDoUsuario($usuarioAdministrador)->count());
    }
    
    public function testDelete()
    {
        $role = UsuarioRole::find(UsuarioRole::ROOT);
        $this->setExpectedException('Exception');
        $role->delete();
    }
}
