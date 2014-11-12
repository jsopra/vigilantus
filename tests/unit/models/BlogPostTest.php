<?php

namespace tests\unit\models;

use app\models\BlogPost;
use Phactory;
use tests\TestCase;
use yii\db\Expression;

class BlogPostTest extends TestCase
{
	public function testAlgumaCoisa()
	{
		$model = new BlogPost;
		$this->assertTrue($model != null);
	}
}