<?php
class BairroTipoTest extends PWebTestCase
{
	public function testIndex()
	{
		$this->open('?r=bairroTipo/index');
		$this->assertTextPresent('Digite aqui um texto breve que explique o que esta tabela lista');
	}
}
