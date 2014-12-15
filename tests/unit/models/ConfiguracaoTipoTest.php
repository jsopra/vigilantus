<?php

namespace tests\unit\models;

use app\models\ConfiguracaoTipo;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class ConfiguracaoTipoTest extends TestCase
{
    public function testGetTiposDeConfiguracao()
    {
        $this->assertEquals(['STRING', 'INTEIRO', 'DECIMAL', 'BOLEANO', 'RANGE', 'TIME'], ConfiguracaoTipo::getTiposDeConfiguracao());
    }
}
