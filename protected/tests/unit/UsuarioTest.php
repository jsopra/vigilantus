<?php

class UsuarioTest extends PDbTestCase
{
	public function testScopes()
	{
		$this->assertEquals(3,Usuario::model()->count());
        $this->assertEquals(2,Usuario::model()->ativo()->count());
        $this->assertEquals(1,Usuario::model()->excluido()->count());
        
        $this->assertEquals(1,Usuario::model()->doEmail('dengue@perspectiva.in')->count());
        $this->assertEquals(0,Usuario::model()->doEmail('dengueKKKK@perspectiva.in')->count());
        
        $usuarioRoot = Usuario::model()->findByPk(1);
        $usuarioAdministrador = Usuario::model()->findByPk(2);
        
        $this->assertEquals(3,Usuario::model()->doNivelDoUsuario($usuarioRoot)->count());
        $this->assertEquals(1,Usuario::model()->doNivelDoUsuario($usuarioAdministrador)->count());
	}
    
    public function testSave() {
        
        $sal = '123123asd';
        $password = 'testeteste';       
        $senhaEncriptada = Usuario::encryptPassword($sal, $password);
        
        $usuario = new Usuario;
        
        $this->assertFalse($usuario->save());
        
        $usuario->id = 4;
        $usuario->nome = 'teste';
        $usuario->login = 'teste';
        $usuario->senha = $password;
        $usuario->senha2 = $password;
        $usuario->sal = $sal;
        $usuario->email = 'teste@teste.com.br';
        $usuario->usuario_role_id = UsuarioRole::ADMINISTRADOR;
        
        $this->assertFalse($usuario->save());
        
        $usuario->usuario_role_id = UsuarioRole::ROOT;

        $this->assertTrue($usuario->save());
        
        $this->assertEquals($senhaEncriptada, $usuario->senha);
    }
    
    public function testUpdate() {
        
        $sal = '123123asd';
        $password = 'testeteste';       
        $senhaEncriptada = Usuario::encryptPassword($sal, $password);
        
        $usuario = Usuario::model()->findByPk(4);
        
        $this->assertInstanceOf('Usuario', $usuario);

        $this->assertEquals($senhaEncriptada, $usuario->senha);
        
        $usuario->scenario = 'login';
        $usuario->ultimo_login = new CDbExpression('NOW()');
        $usuario->save();
        
        $this->assertEquals($senhaEncriptada, $usuario->senha);
    }
    
    public function testDelete() {
        
        $usuario = Usuario::model()->findByPk(4);
        
        $this->assertInstanceOf('Usuario', $usuario);
        
        $this->assertFalse($usuario->excluido);
        
        $this->assertTrue($usuario->delete());
        
        $this->assertTrue($usuario->excluido);
    }
}