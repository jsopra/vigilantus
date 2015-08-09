<?php

namespace tests\unit\models;

use app\models\ConfiguracaoTipo;
use Phactory;
use perspectiva\phactory\Test;
use yii\db\Expression;

class ConfiguracaoTipoTest extends Test
{
    public function testGetTiposDeConfiguracao()
    {
        $this->assertEquals(['STRING', 'INTEIRO', 'DECIMAL', 'BOLEANO', 'RANGE', 'TIME'], ConfiguracaoTipo::getTiposDeConfiguracao());
    }
}
