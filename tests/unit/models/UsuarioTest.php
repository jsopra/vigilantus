<?php

namespace tests\unit\models;

use app\models\Usuario;
use app\models\UsuarioRole;
use yii\codeception\TestCase;
use yii\db\Expression;

class UsuarioTest extends TestCase
{
    public function testScopes()
    {
        $this->assertEquals(4, Usuario::find()->count());
        $this->assertEquals(3, Usuario::find()->ativo()->count());
        $this->assertEquals(1, Usuario::find()->excluido()->count());

        $this->assertEquals(1, Usuario::find()->where(['email' => 'dengue@perspectiva.in'])->count());
        $this->assertEquals(0, Usuario::find()->where(['email' => 'dengueKKKK@perspectiva.in'])->count());

        $usuarioRoot = Usuario::find(1);
        $usuarioAdministrador = Usuario::find(2);

        $this->assertEquals(4, Usuario::find()->doNivelDoUsuario($usuarioRoot)->count());
        $this->assertEquals(1, Usuario::find()->doNivelDoUsuario($usuarioAdministrador)->count());
    }

    public function testSave()
    {
        $sal = '123123asd';
        $password = 'testeteste';
        $senhaEncriptada = Usuario::encryptPassword($sal, $password);

        $usuario = new Usuario;

        $this->assertFalse($usuario->save());

        $usuario->id = 5;
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

    public function testUpdate()
    {
        $sal = 'asd7y%i3';
        $password = 'administrador';
        $senhaEncriptada = Usuario::encryptPassword($sal, $password);

        $usuario = Usuario::find(2);

        $this->assertInstanceOf('app\models\Usuario', $usuario);

        $this->assertEquals($senhaEncriptada, $usuario->senha);

        $usuario->ultimo_login = new Expression('NOW()');
        $usuario->save();

        $this->assertEquals($senhaEncriptada, $usuario->senha);
    }

    public function testDelete()
    {
        $usuario = Usuario::find(2);

        $this->assertInstanceOf('app\models\Usuario', $usuario);

        $this->assertFalse($usuario->excluido);

        $this->assertTrue($usuario->delete());

        $this->assertTrue($usuario->excluido);
    }
}
