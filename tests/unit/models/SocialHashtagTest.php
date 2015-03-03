<?php

namespace tests\unit\models;

use app\models\SocialHashtag;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class SocialHashtagTest extends TestCase
{
	public function testAlgumaCoisa()
	{
		$model = new SocialHashtag;
		$this->assertTrue($model != null);
	}
}