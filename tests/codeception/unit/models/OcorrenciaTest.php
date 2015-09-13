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

    public function testSeNaoTemIDTipoProblemaRequerDescricaoTipoProblema()
    {
        $model = Phactory::ocorrencia();
        $model->ocorrencia_tipo_problema_id = null;
        $model->descricao_outro_tipo_problema = null;

        $this->assertFalse($model->save());

        $model->descricao_outro_tipo_problema = 'Outro tipo de problema';

        $this->assertTrue($model->save());
    }

    public function testSeTemIDTipoProblemaDescricaoTipoProblemaTemQueSerRemovido()
    {
        $model = Phactory::ocorrencia();
        $model->ocorrencia_tipo_problema_id = Phactory::ocorrenciaTipoProblema()->id;
        $model->descricao_outro_tipo_problema = 'Deve eliminar esse valor';

        $this->assertTrue($model->save());
        $this->assertNull($model->descricao_outro_tipo_problema);
    }

    public function testGetDescricaoTipoProblema()
    {
        $tipoProblema = Phactory::ocorrenciaTipoProblema(['nome' => 'TIPO PREFEITURA']);
        $model = Phactory::ocorrencia([
            'ocorrenciaTipoProblema' => $tipoProblema,
        ]);
        $this->assertEquals('TIPO PREFEITURA', $model->getDescricaoTipoProblema());

        $model->ocorrencia_tipo_problema_id = null;
        $model->descricao_outro_tipo_problema = 'TIPO DIGITADO';
        $this->assertTrue($model->save());
        $model->refresh();
        $this->assertEquals('TIPO DIGITADO', $model->getDescricaoTipoProblema());
    }
}
