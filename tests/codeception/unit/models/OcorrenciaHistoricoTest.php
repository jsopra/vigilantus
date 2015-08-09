<?php

namespace tests\unit\models;

use app\models\OcorrenciaHistorico;
use Phactory;
use fidelize\phactory\ActiveRecordTest;
use yii\db\Expression;

class OcorrenciaHistoricoTest extends ActiveRecordTest
{
    public function testDaDenuncia()
    {
        //ao criar a denuncia, cria um historico automaticamente
        $denunciaA = Phactory::ocorrencia();
        $denunciaB = Phactory::ocorrencia();

        Phactory::OcorrenciaHistorico(['ocorrencia' => $denunciaA]);
        Phactory::OcorrenciaHistorico(['ocorrencia' => $denunciaA]);
        Phactory::OcorrenciaHistorico(['ocorrencia' => $denunciaA]);

        $this->assertEquals(5, OcorrenciaHistorico::find()->count());
        $this->assertEquals(4, OcorrenciaHistorico::find()->daOcorrencia($denunciaA->id)->count());
        $this->assertEquals(1, OcorrenciaHistorico::find()->daOcorrencia($denunciaB->id)->count());
    }
}
