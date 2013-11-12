<?php
class UsuarioTest extends PWebTestCase
{
	public function testIndex()
	{
		$this->open('?r=usuario/index');
		$this->assertTextPresent('Digite aqui um texto breve que explique o que esta tabela lista');
	}
}
