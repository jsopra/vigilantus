<?php

namespace tests\unit\models;

use app\models\Ocorrencia;
use Phactory;
use perspectiva\phactory\ActiveRecordTest;
use yii\db\Expression;
use app\models\OcorrenciaStatus;
use app\models\OcorrenciaHistorico;

class OcorrenciaTest extends ActiveRecordTest
{
	public function testSave()
	{
		$model = Phactory::ocorrencia();

        $this->assertEquals(1, OcorrenciaHistorico::find()->daOcorrencia($model->id)->count());

        $model->status = OcorrenciaStatus::APROVADA;

        $this->assertTrue($model->save());

        $this->assertEquals(2, OcorrenciaHistorico::find()->daOcorrencia($model->id)->count());
	}
}
