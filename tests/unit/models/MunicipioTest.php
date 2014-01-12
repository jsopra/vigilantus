<?php

namespace tests\unit\models;

use app\models\Municipio;
use yii\codeception\TestCase;

class MunicipioTest extends TestCase
{
    public function testDelete()
    {
        $municipio = Municipio::find(1);
        $this->setExpectedException('Exception');
        $municipio->delete();
    }
}
