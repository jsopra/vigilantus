<?php

namespace tests\unit\models;

use app\models\DenunciaTipoProblema;
use Phactory;
use fidelize\phactory\ActiveRecordTest;
use yii\db\Expression;

class DenunciaTipoProblemaTest extends ActiveRecordTest
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

        Phactory::denuncia(['denunciaTipoProblema' => $tipo]);

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
