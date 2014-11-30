<?php

namespace tests\unit\models;

use app\models\Rua;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class RuaTest extends TestCase
{
    public function testScopeDaRua()
    {
        Phactory::rua(['nome' => 'Teste A']);
        Phactory::rua(['nome' => 'Teste B']);
        Phactory::rua(['nome' => 'Teste A']);

        $this->assertEquals(2, Rua::find()->daRua('Teste A')->count());
        $this->assertEquals(1, Rua::find()->daRua('Teste B')->count());
    }
}
