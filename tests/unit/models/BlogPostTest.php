<?php

namespace tests\unit\models;

use app\models\BlogPost;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class BlogPostTest extends TestCase
{
	public function testAlgumaCoisa()
	{
		$model = new BlogPost;
		$this->assertTrue($model != null);
	}
}