<?= "<?php\n"; ?>

namespace tests\unit\models;

use app\models\<?= $className; ?>;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class <?php echo $className; ?>Test extends TestCase
{
	public function testAlgumaCoisa()
	{
		$model = new <?php echo $className; ?>;
		$this->assertTrue($model != null);
	}
}