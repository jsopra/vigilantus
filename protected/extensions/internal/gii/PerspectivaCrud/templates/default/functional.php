<?php echo "<?php\n"; ?>
class <?php echo $this->modelClass; ?>Test extends PWebTestCase
{
	public function testIndex()
	{
		$this->open('?r=<?php echo $this->getControllerID(); ?>/index');
		$this->assertTextPresent('Digite aqui um texto breve que explique o que esta tabela lista');
	}
}
