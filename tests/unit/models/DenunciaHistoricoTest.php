<?php

namespace tests\unit\models;

use app\models\DenunciaHistorico;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class DenunciaHistoricoTest extends TestCase
{
    public function testDaDenuncia()
    {
        //ao criar a denuncia, cria um historico automaticamente
        $denunciaA = Phactory::denuncia();
        $denunciaB = Phactory::denuncia();

        Phactory::denunciaHistorico(['denuncia_id' => $denunciaA]);
        Phactory::denunciaHistorico(['denuncia_id' => $denunciaA]);
        Phactory::denunciaHistorico(['denuncia_id' => $denunciaA]);

        $this->assertEquals(5, DenunciaHistorico::find()->count());

        $this->assertEquals(4, DenunciaHistorico::find()->daDenuncia($denunciaA->id)->count());

        $this->assertEquals(1, DenunciaHistorico::find()->daDenuncia($denunciaB->id)->count());
    }
}
