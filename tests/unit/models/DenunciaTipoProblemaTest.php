<?php

namespace tests\unit\models;

use app\models\DenunciaTipoProblema;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class DenunciaTipoProblemaTest extends TestCase
{
    /*
	public function testScopeAtivos()
	{
		Phactory::denunciaTipoProblema(['ativo' => true]);
        Phactory::denunciaTipoProblema(['ativo' => true]);
        Phactory::denunciaTipoProblema(['ativo' => false]);

        $this->assertEquals(3, DenunciaTipoProblema::find()->count());
        $this->assertEquals(2, DenunciaTipoProblema::find()->ativos()->count());
	}
    */

    public function testExclusaoException()
    {
        $tipo = Phactory::denunciaTipoProblema(['ativo' => true]);

        Phactory::denuncia(['denuncia_tipo_problema_id' => $tipo]);

        $this->setExpectedException('Exception');

        $tipo->delete();
    }

    public function testExclusao()
    {
        $tipo = Phactory::denunciaTipoProblema(['ativo' => true]);

        Phactory::denuncia();

        $this->assertEquals(1, $tipo->delete());
    }
}
