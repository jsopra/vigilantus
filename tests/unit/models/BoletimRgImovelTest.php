<?php

namespace tests\unit\models;

use Phactory;
use app\models\BoletimRgFechamento;
use app\models\BoletimRgImovel;
use tests\TestCase;

class BoletimRgImovelTest extends TestCase
{
    public function testPrepararRua()
    {
        $ruaExistente = Phactory::rua(['nome' => 'Avenida Existente']);

        $imovelRg = Phactory::boletimRgImovel();

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
        $imovelRg = Phactory::boletimRgImovel();

        // Assegura que o imóvel tem os mesmos dados que o imovelRg
        $imovel = $imovelRg->imovel;
        $imovel->bairro_quarteirao_id = $imovelRg->boletimRg->bairro_quarteirao_id;
        $imovel->numero = $imovelRg->imovel_numero;
        $imovel->sequencia = $imovelRg->imovel_seq;
        $imovel->complemento = $imovelRg->imovel_complemento;
        $imovel->rua_id = $imovelRg->rua_id;
        $imovel->municipio_id = $imovelRg->municipio_id;
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
        $this->assertNull(BoletimRgFechamento::find()->one());

        $imovelRg = Phactory::boletimRgImovel();
        $quantidade = 5;

        // Insere mais 4 imóveis no mesmo boletimRg
        for ($i = 1; $i < $quantidade; $i++) {

            $rua = Phactory::rua();
            $imovel = Phactory::imovel(['rua_id' => $rua->id]);
            $parametrosDiferentes = [
                'id' => null,
                'rua_id' => $rua->id,
                'imovel_id' => $imovel->id,
            ];
            Phactory::boletimRgImovel($imovelRg->attributes + $parametrosDiferentes);
        }

        $boletimFechamento = BoletimRgFechamento::findOne(
            [
                'boletim_rg_id' => $imovelRg->boletim_rg_id,
            ]
        );

        $this->assertEquals($quantidade, $boletimFechamento->quantidade);
    }

    public function testSaveDecrementaBoletimFechamento()
    {
        $this->assertNull(BoletimRgFechamento::find()->one());

        $imovelRg = Phactory::boletimRgImovel();
        $quantidade = 5;

        // Insere mais 4 imóveis no mesmo boletimRg
        for ($i = 1; $i < $quantidade; $i++) {

            $rua = Phactory::rua();
            $imovel = Phactory::imovel(['rua_id' => $rua->id]);

            $parametrosDiferentes = [
                'id' => null,
                'rua_id' => $rua->id,
                'imovel_id' => $imovel->id,
            ];
            
            Phactory::boletimRgImovel($imovelRg->attributes + $parametrosDiferentes);
        }

        $this->assertEquals(1, $imovelRg->delete());

        $boletimFechamento = BoletimRgFechamento::findOne(
            [
                'boletim_rg_id' => $imovelRg->boletim_rg_id,
            ]
        );

        $this->assertEquals($quantidade - 1, $boletimFechamento->quantidade);
    }
}
