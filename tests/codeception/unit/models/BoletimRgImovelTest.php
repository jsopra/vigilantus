<?php

namespace tests\unit\models;

use Phactory;
use app\models\BoletimRgFechamento;
use app\models\BoletimRgImovel;
use fidelize\phactory\ActiveRecordTest;

class BoletimRgImovelTest extends ActiveRecordTest
{
    public function testPrepararRua()
    {
        $cliente = Phactory::cliente();

        $ruaExistente = Phactory::rua(['nome' => 'Avenida Existente', 'cliente_id' => $cliente->id]);

        $imovelRg = Phactory::boletimRgImovel(['cliente_id' => $cliente->id]);

        // Rua que já existe, seta o ID
        $this->assertTrue($imovelRg->prepararRua('Avenida Existente'));
        $this->assertEquals($ruaExistente->id, $imovelRg->rua_id);

        // Rua inexistente, cria
        $this->assertTrue($imovelRg->prepararRua('Servidão Nova'));
        $this->assertNotEquals($ruaExistente->id, $imovelRg->rua_id);
        $this->assertNotNull($imovelRg->rua_id);
    }

    public function testPrepararImovel()
    {
        $cliente = Phactory::cliente();

        $imovelRg = Phactory::boletimRgImovel(['cliente_id' => $cliente->id]);

        // Assegura que o imóvel tem os mesmos dados que o imovelRg
        $imovel = $imovelRg->imovel;
        $imovel->bairro_quarteirao_id = $imovelRg->boletimRg->bairro_quarteirao_id;
        $imovel->numero = $imovelRg->imovel_numero;
        $imovel->sequencia = $imovelRg->imovel_seq;
        $imovel->complemento = $imovelRg->imovel_complemento;
        $imovel->rua_id = $imovelRg->rua_id;
        $imovel->cliente_id = $cliente->id;
        $this->assertTrue($imovel->save());

        // Procura imóvel existente
        $imovelRg->imovel_id = null;

        $this->assertTrue($imovelRg->prepararImovel());
        $this->assertEquals($imovel->id, $imovelRg->imovel_id);

        // Insere novo imóvel
        $imovelRg->rua_id = Phactory::rua()->id;
        $this->assertTrue($imovelRg->prepararImovel());
        $this->assertNotEquals($imovel->id, $imovelRg->imovel_id);
        $this->assertNotNull($imovelRg->imovel_id);
    }

    public function testSaveProduzBoletimFechamento()
    {
        $imovelRg = Phactory::boletimRgImovel();
        $quantidade = 5;

        // Insere mais 4 imóveis no mesmo boletimRg
        for ($i = 1; $i < $quantidade; $i++) {

            $rua = Phactory::rua();
            $imovel = Phactory::imovel(['rua' => $rua]);
            $parametrosDiferentes = [
                'rua' => $rua,
                'imovel' => $imovel,
            ];
            $atributos = $imovelRg->attributes;
            unset($atributos['id']);
            Phactory::boletimRgImovel($atributos + $parametrosDiferentes);
        }

        $boletimFechamento = BoletimRgFechamento::find()->where(['boletim_rg_id' => $imovelRg->boletim_rg_id])->one();

        $this->assertEquals($quantidade, $boletimFechamento->quantidade);
    }

    public function testSaveDecrementaBoletimFechamento()
    {
        $this->assertNull(BoletimRgFechamento::find()->one());

        $imovelRg = Phactory::boletimRgImovel();

        // Insere mais 4 imóveis no mesmo boletimRg
        for ($i = 1; $i < 5; $i++) {

            $rua = Phactory::rua();
            $imovel = Phactory::imovel(['rua' => $rua]);

            $parametrosDiferentes = [
                'rua' => $rua,
                'imovel' => $imovel,
            ];
            $attributes = $imovelRg->attributes;
            unset($attributes['id']);

            Phactory::boletimRgImovel($attributes + $parametrosDiferentes);
        }

        $this->assertEquals(1, $imovelRg->delete());

        $boletimFechamento = BoletimRgFechamento::find()->where(['boletim_rg_id' => $imovelRg->boletim_rg_id])->one();

        $this->assertEquals(4, $boletimFechamento->quantidade);
    }
}
