<?php

namespace tests\unit\models;

use app\models\Denuncia;
use Phactory;
use fidelize\phactory\ActiveRecordTest;
use yii\db\Expression;
use app\models\DenunciaStatus;
use app\models\DenunciaHistorico;

class DenunciaTest extends ActiveRecordTest
{
	public function testSave()
	{
		$model = Phactory::denuncia();

        $this->assertEquals(1, DenunciaHistorico::find()->daDenuncia($model->id)->count());

        $model->status = DenunciaStatus::APROVADA;

        $this->assertTrue($model->save());

        $this->assertEquals(2, DenunciaHistorico::find()->daDenuncia($model->id)->count());
	}
}
