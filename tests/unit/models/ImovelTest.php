<?php

namespace tests\unit\models;

use app\models\Imovel;
use Phactory;
use yii\codeception\TestCase;
use yii\db\Expression;

class ImovelTest extends TestCase
{
    public function testScopeDoTipoLira()
    {
        Phactory::imovel(['imovel_lira' => false]);
        Phactory::imovel(['imovel_lira' => false]);
        Phactory::imovel(['imovel_lira' => false]);
        Phactory::imovel(['imovel_lira' => true]);

        $this->assertEquals(3, Imovel::find()->doTipoLira(false)->count());
        $this->assertEquals(1, Imovel::find()->doTipoLira(true)->count());
    }

    public function testScopeDaRua()
    {
        $ruaA = Phactory::rua();
        $ruaB = Phactory::rua();

        Phactory::imovel(['rua_id' => $ruaA->id]);
        Phactory::imovel(['rua_id' => $ruaA->id]);
        Phactory::imovel(['rua_id' => $ruaB->id]);

        $this->assertEquals(2, Imovel::find()->daRua($ruaA->id)->count());
        $this->assertEquals(1, Imovel::find()->daRua($ruaB->id)->count());
    }

    public function testScopeDoQuarteirao()
    {
        $quarteiraoA = Phactory::bairroQuarteirao();
        $quarteiraoB = Phactory::bairroQuarteirao();

        Phactory::imovel(['bairro_quarteirao_id' => $quarteiraoA->id]);
        Phactory::imovel(['bairro_quarteirao_id' => $quarteiraoA->id]);
        Phactory::imovel(['bairro_quarteirao_id' => $quarteiraoB->id]);

        $this->assertEquals(2, Imovel::find()->doQuarteirao($quarteiraoA->id)->count());
        $this->assertEquals(1, Imovel::find()->doQuarteirao($quarteiraoB->id)->count());
    }

    public function testScopeDoNumero()
    {
        Phactory::imovel(['numero' => '10']);
        Phactory::imovel(['numero' => '12']);

        $this->assertEquals(1, Imovel::find()->doNumero(10)->count());
        $this->assertEquals(1, Imovel::find()->doNumero(12)->count());
    }

    public function testScopeDaSeq()
    {
        Phactory::imovel(['sequencia' => '10']);
        Phactory::imovel(['sequencia' => '12']);

        $this->assertEquals(1, Imovel::find()->daSeq(10)->count());
        $this->assertEquals(1, Imovel::find()->daSeq(12)->count());
    }

    public function testScopeDoComplemento()
    {
        Phactory::imovel(['complemento' => 'AP 607']);
        Phactory::imovel(['complemento' => 'AP 702']);

        $this->assertEquals(1, Imovel::find()->doComplemento('AP 607')->count());
        $this->assertEquals(1, Imovel::find()->doComplemento('AP 702')->count());
    }
}
