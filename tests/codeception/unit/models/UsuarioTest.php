<?php

namespace tests\unit\models;

use app\models\Usuario;
use app\models\UsuarioRole;
use Phactory;
use perspectiva\phactory\ActiveRecordTest;
use yii\db\Expression;

class UsuarioTest extends ActiveRecordTest
{
    public function testScopes()
    {
        // Tem um usuário pré-cadastrado como root e com o email correto
        Phactory::usuario('gerente');
        $usuarioAdministrador = Phactory::usuario('administrador');
        $usuarioRoot = Phactory::usuario('root', ['excluido' => 1]);

        $this->assertEquals(4, Usuario::find()->count());
        $this->assertEquals(3, Usuario::find()->ativo()->count());
        $this->assertEquals(1, Usuario::find()->excluido()->count());

        $this->assertEquals(1, Usuario::find()->where(['email' => 'dengue@perspectiva.in'])->count());
        $this->assertEquals(0, Usuario::find()->where(['email' => 'dengueKKKK@perspectiva.in'])->count());

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

        $usuario->nome = 'teste';
        $usuario->login = 'teste';
        $usuario->senha = $senha;
        $usuario->confirmacao_senha = $senha;
        $usuario->sal = $sal;
        $usuario->email = 'teste@teste.com.br';
        $usuario->usuario_role_id = UsuarioRole::ADMINISTRADOR;
        $usuario->cliente_id = Phactory::cliente()->id;

        $this->assertTrue($usuario->save());

        $this->assertEquals($senha, $usuario->senha);
        $this->assertEquals($senhaCriptografada, $usuario->senha_criptografada);
    }

    public function testGetRole()
    {
        $usuario = Phactory::usuario('gerente');

        $this->assertEquals('Gerente', $usuario->role->nome);
    }

    public function testGetRBACRole()
    {
        $usuario = Phactory::usuario('gerente');

        $this->assertEquals('Gerente', $usuario->getRBACRole());
    }

    public function testModuloIsHabilitado()
    {
        $clienteA = Phactory::cliente();
        $clienteB = Phactory::cliente();

        $modulo = Phactory::modulo();

        Phactory::clienteModulo(['cliente' => $clienteA, 'modulo' => $modulo]);

        $usuarioA = Phactory::usuario('administrador', ['cliente' => $clienteA]);
        $usuarioB = Phactory::usuario('administrador', ['cliente' => $clienteB]);

        $this->assertTrue($usuarioA->moduloIsHabilitado($modulo->id, $clienteA));
        $this->assertFalse($usuarioB->moduloIsHabilitado($modulo->id, $clienteB));
    }
}
