<?php echo "<?php\n"; ?>

class <?php echo $modelClass; ?>Test extends FDbTestCase
{
	public function testAlgumaCoisa()
	{
		$model = new <?php echo $modelClass; ?>;
		$this->assertTrue($model != null);
	}
}