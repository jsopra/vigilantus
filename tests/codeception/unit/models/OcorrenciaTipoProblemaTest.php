<?php

namespace tests\unit\models;

use app\models\OcorrenciaTipoProblema;
use Phactory;
use fidelize\phactory\ActiveRecordTest;
use yii\db\Expression;

class OcorrenciaTipoProblemaTest extends ActiveRecordTest
{
    /*
	public function testScopeAtivos()
	{
		Phactory::ocorrenciaTipoProblema(['ativo' => true]);
        Phactory::ocorrenciaTipoProblema(['ativo' => true]);
        Phactory::ocorrenciaTipoProblema(['ativo' => false]);

        $this->assertEquals(3, DenunciaTipoProblema::find()->count());
        $this->assertEquals(2, DenunciaTipoProblema::find()->ativos()->count());
	}
    */

    public function testExclusaoException()
    {
        $tipo = Phactory::ocorrenciaTipoProblema(['ativo' => true]);

        Phactory::ocorrencia(['ocorrenciaTipoProblema' => $tipo]);

        $this->setExpectedException('Exception');

        $tipo->delete();
    }

    public function testExclusao()
    {
        $tipo = Phactory::ocorrenciaTipoProblema(['ativo' => true]);

        Phactory::ocorrencia();

        $this->assertEquals(1, $tipo->delete());
    }
}
