<?php

namespace tests\unit\models;

use app\models\Usuario;
use app\models\UsuarioRole;
use Phactory;
use tests\TestCase;
use yii\db\Expression;

class UsuarioTest extends TestCase
{
    public function testScopes()
    {
        // Tem um usuário pré-cadastrado como root e com o email correto
        Phactory::usuario('gerente');
        Phactory::usuario('administrador');
        Phactory::usuario('root', ['excluido' => 1]);

        $this->assertEquals(4, Usuario::find()->count());
        $this->assertEquals(3, Usuario::find()->ativo()->count());
        $this->assertEquals(1, Usuario::find()->excluido()->count());

        $this->assertEquals(1, Usuario::find()->where(['email' => 'dengue@perspectiva.in'])->count());
        $this->assertEquals(0, Usuario::find()->where(['email' => 'dengueKKKK@perspectiva.in'])->count());

        $usuarioRoot = Usuario::findOne(1);
        $usuarioAdministrador = Usuario::findOne(2);

        $this->assertEquals(4, Usuario::find()->doNivelDoUsuario($usuarioRoot)->count());
        $this->assertEquals(1, Usuario::find()->doNivelDoUsuario($usuarioAdministrador)->count());
    }

    public function testSave()
    {
        $sal = 'sal';
        $senha = 'senha8caracteres';
        $senhaCriptografada = Usuario::encryptPassword($sal, $senha);

        $usuario = new Usuario;

        $this->assertFalse($usuario->save());

        $usuario->id = 5;
        $usuario->nome = 'teste';
        $usuario->login = 'teste';
        $usuario->senha = $senha;
        $usuario->confirmacao_senha = $senha;
        $usuario->sal = $sal;
        $usuario->email = 'teste@teste.com.br';
        $usuario->usuario_role_id = UsuarioRole::ADMINISTRADOR;

        $this->assertFalse($usuario->save());

        $usuario->usuario_role_id = UsuarioRole::ROOT;

        $this->assertTrue($usuario->save());

        $this->assertEquals($senha, $usuario->senha);
        $this->assertEquals($senhaCriptografada, $usuario->senha_criptografada);
    }
}
