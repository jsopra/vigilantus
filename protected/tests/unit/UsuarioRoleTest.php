<?php

class UsuarioRoleTest extends PDbTestCase
{
	public function testRolesCadastradas()
	{
		$this->assertEquals(4, UsuarioRole::model()->count());
        
        $this->assertEquals('Root', UsuarioRole::model()->findByPk(UsuarioRole::ROOT)->nome);
        $this->assertEquals('Administrador', UsuarioRole::model()->findByPk(UsuarioRole::ADMINISTRADOR)->nome);
        $this->assertEquals('Gerente', UsuarioRole::model()->findByPk(UsuarioRole::GERENTE)->nome);
        $this->assertEquals('Usuário', UsuarioRole::model()->findByPk(UsuarioRole::USUARIO)->nome);
	}
    
    public function testScopes() {
        
        $usuarioRoot = Usuario::model()->findByPk(1);
        $usuarioAdministrador = Usuario::model()->findByPk(2);
        
        $this->assertEquals(4,UsuarioRole::model()->doNivelDoUsuario($usuarioRoot)->count());
        $this->assertEquals(3,UsuarioRole::model()->doNivelDoUsuario($usuarioAdministrador)->count());
    }
    
    public function testDelete() {
        
        $role = UsuarioRole::model()->findByPk(UsuarioRole::ROOT);
        $this->setExpectedException('Exception');
        $role->delete();
    }
}