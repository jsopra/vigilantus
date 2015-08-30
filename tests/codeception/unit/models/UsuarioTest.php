<?php

namespace tests\unit\models;

use app\models\Usuario;
use app\models\UsuarioRole;
use Phactory;
use perspectiva\phactory\ActiveRecordTest;
use yii\db\Expression;

class UsuarioTest extends ActiveRecordTest
{
    public function testGetAuthKeyRetornaSal()
    {
        $usuario = Phactory::unsavedUsuario();
        $this->assertEquals($usuario->sal, $usuario->getAuthKey());
    }

    public function testGetIdRetornaId()
    {
        // Esse getId() é usado pela autenticação e faz parte de uma interface
        // por isso soa meio "bobo" ter um getId que retorna id
        $usuario = Phactory::unsavedUsuario(['id' => 418]);
        $this->assertEquals(418, $usuario->getId());
    }

    public function testValidateAuthKeyVerificaSal()
    {
        $usuario = Phactory::unsavedUsuario(['sal' => 'sal válido']);
        $this->assertTrue($usuario->validateAuthKey('sal válido'));
        $this->assertFalse($usuario->validateAuthKey('sal inválido'));
    }

    public function testFindIdentityRetornaUsuario()
    {
        $usuario = Phactory::usuario();
        $this->assertTrue($usuario->equals(Usuario::findIdentity($usuario->id)));
    }

    public function testFindIdentityByAccessTokenBuscaPeloTokenApi()
    {
        $usuario = Phactory::usuario();
        $this->assertTrue($usuario->equals(Usuario::findIdentityByAccessToken($usuario->token_api)));
    }

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

    public function testValidatePassword()
    {
        $usuario = Phactory::unsavedUsuario();
        $usuario->sal = 'sal';
        $usuario->senha_criptografada = Usuario::encryptPassword('sal', 'senha');

        $this->assertTrue($usuario->validatePassword('senha'));
        $this->assertFalse($usuario->validatePassword('senha inválida'));
    }

    public function testGetRole()
    {
        $usuario = Phactory::usuario('gerente');

        $this->assertEquals('Gerente', $usuario->role->nome);
    }

    public function testChangePasswordLancaExcecaoSeNaoTemSalMasRegistroExiste()
    {
        $this->setExpectedException('Exception');
        $usuario = Phactory::usuario();
        $usuario->sal = null; // como se tivesse buscado sem a coluna "sal"
        $usuario->changePassword('senha nova', 'senha nova');
    }

    public function testChangePasswordGeraSalSeNaoTiver()
    {
        $usuario = Phactory::unsavedUsuario(); // registro novo
        $usuario->sal = null;
        $usuario->changePassword('senha nova', 'senha nova');
        $this->assertNotNull($usuario->sal);
    }

    public function testChangePasswordAdicionaErroSeAsSenhasNaoConferem()
    {
        $usuario = Phactory::unsavedUsuario();
        $usuario->changePassword('senha FOO', 'senha BAR');
        $this->assertContains('A confirmação de senha não confere', $usuario->getErrors('senha'));
    }

    public function testChangePasswordMudaASenhaCriptogfrafada()
    {
        $usuario = Phactory::unsavedUsuario();
        $senhaAntiga = $usuario->senha_criptografada;
        $usuario->changePassword('senha nova', 'senha nova');
        $senhaNova = $usuario->senha_criptografada;
        $this->assertNotEquals($senhaAntiga, $senhaNova);
    }

    public function testDelete()
    {
        $usuario = Phactory::usuario();
        $this->assertFalse((bool) $usuario->excluido);
        $usuario->delete();
        $this->assertTrue($usuario->excluido);
    }

    public function testGetRBACRole()
    {
        $usuarioComRole = Phactory::unsavedUsuario('gerente');
        $usuarioSemRole = Phactory::unsavedUsuario();
        $usuarioSemRole->usuario_role_id = null;

        $this->assertEquals('Gerente', $usuarioComRole->getRBACRole());
        $this->assertNull($usuarioSemRole->getRBACRole());
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
        $this->assertTrue($usuarioA->moduloIsHabilitado($modulo->id));
        $this->assertFalse($usuarioB->moduloIsHabilitado($modulo->id, $clienteB));
    }
}
