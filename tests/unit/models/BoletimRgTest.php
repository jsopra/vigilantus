<?php

// namespace tests\unit\models;

// use app\models\BoletimRg;
// use app\models\BoletimRgFechamento;
// use app\models\BoletimRgImoveis;
// use app\models\Bairro;
// use app\models\BairroRuaImovel;
// use app\models\BairroRua;
// use yii\codeception\TestCase;

// class BoletimRgTest extends TestCase
// {
//     public $boletimA;
//     public $boletimB;
    
//     public function validateMigrate() {
        
//         $bairro = Bairro::find(1);
//         $this->assertInstanceOf('app\models\Bairro', $bairro);
        
        

//         $this->assertFalse($boletim->validate());

//         $this->assertEquals(6, count($boletim->errors));
        
//         $this->assertArrayHasKey('folha', $boletim->errors);
//         $this->assertArrayHasKey('bairro_id', $boletim->errors);
//         $this->assertArrayHasKey('municipio_id', $boletim->errors);
//         $this->assertArrayHasKey('bairro_quarteirao_id', $boletim->errors);
//         $this->assertArrayHasKey('imoveis', $boletim->errors);
//         $this->assertArrayHasKey('data', $boletim->errors);
        
        

//         $this->assertFalse($boletim->save());
        
//         $this->assertArrayHasKey('imoveis', $boletim->errors);
        
//         $boletim->imoveis = array(
//             array(
//                 'rua' => 'Rio de Janeiro',
//                 'numero' => '176',
//                 'seq' => null,
//                 'complemento' => 'AP 705',
//                 'imovel_lira' => false,
//                 'imovel_tipo' => 1,
//             ),
//             array(
//                 'rua' => 'Rio de Janeiro',
//                 'numero' => '176',
//                 'seq' => null,
//                 'complemento' => 'AP 704',
//                 'imovel_lira' => false,
//                 'imovel_tipo' => 1,
//             ),
//             array(
//                 'rua' => 'Rio de Janeiro',
//                 'numero' => '176',
//                 'seq' => null,
//                 'complemento' => 'AP 703',
//                 'imovel_lira' => false,
//                 'imovel_tipo' => 1,
//             ),
//             array(
//                 'rua' => 'Rio de Janeiro',
//                 'numero' => '173',
//                 'seq' => null,
//                 'complemento' => null,
//                 'imovel_lira' => true,
//                 'imovel_tipo' => 2,
//             ),
//         );
        
//         $this->assertTrue($boletim->save());
        
//         $boletim = BoletimRg::find(1);
        
//         $this->assertInstanceOf('app\models\BoletimRg', $boletim);
        
//         $this->assertEquals(4, $boletim->quantidadeImoveis);
        
//         $qtdeImoveisFechamento = BoletimRgFechamento::find()->doBoletim($boletim->id)->doTipoDeImovel(1)->doTipoLira(false)->count();
//         $this->assertEquals(1, $qtdeImoveisFechamento);
        
//         $imovelFechamento = BoletimRgFechamento::find()->doBoletim($boletim->id)->doTipoDeImovel(1)->doTipoLira(false)->one();
//         $this->assertEquals(3, $imovelFechamento->quantidade);
     
//         $qtdeImoveisFechamento = BoletimRgFechamento::find()->doBoletim($boletim->id)->doTipoDeImovel(1)->doTipoLira(true)->count();
//         $this->assertEquals(0, $qtdeImoveisFechamento);
        
//         $qtdeImoveisFechamento = BoletimRgFechamento::find()->doBoletim($boletim->id)->doTipoDeImovel(2)->doTipoLira(true)->count();
//         $this->assertEquals(1, $qtdeImoveisFechamento);
        
//         $imovelFechamento = BoletimRgFechamento::find()->doBoletim($boletim->id)->doTipoDeImovel(2)->doTipoLira(true)->one();
//         $this->assertEquals(1, $imovelFechamento->quantidade);
        
//         $qtdeImoveisFechamento = BoletimRgFechamento::find()->doBoletim($boletim->id)->doTipoDeImovel(2)->doTipoLira(false)->count();
//         $this->assertEquals(0, $qtdeImoveisFechamento);
        
//         $rua = BairroRua::find()->doBairro($bairro->id)->daRua('Rio de Janeiro')->one();
//         $this->assertInstanceOf('app\models\BairroRua', $rua);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(176)->doComplemento('AP 705')->doTipoLira(false)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(176)->doComplemento('AP 704')->doTipoLira(false)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(176)->doComplemento('AP 703')->doTipoLira(false)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(173)->doTipoLira(false)->one();
//         $this->assertNull($imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(173)->doTipoLira(true)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $this->boletimA = $boletim->id;
        
//     }
    
//     public function testCRUD()
//     {
//         /*
//          * boletim 1
//          */
//         $bairro = Bairro::find(1);
//         $this->assertInstanceOf('app\models\Bairro', $bairro);
        
//         $boletim = new BoletimRg;

//         $this->assertFalse($boletim->validate());

//         $this->assertEquals(6, count($boletim->errors));
        
//         $this->assertArrayHasKey('folha', $boletim->errors);
//         $this->assertArrayHasKey('bairro_id', $boletim->errors);
//         $this->assertArrayHasKey('municipio_id', $boletim->errors);
//         $this->assertArrayHasKey('bairro_quarteirao_id', $boletim->errors);
//         $this->assertArrayHasKey('imoveis', $boletim->errors);
//         $this->assertArrayHasKey('data', $boletim->errors);
        
//         $boletim->folha = '001';
//         $boletim->municipio_id = 1;
//         $boletim->inserido_por = 1;
//         $boletim->bairro_id = $bairro->id;
//         $boletim->bairro_quarteirao_id = 180;
//         $boletim->data = date('d/m/Y');
//         $boletim->inserido_por = 1;
//         $boletim->categoria_id = $bairro->bairro_categoria_id;

//         $this->assertFalse($boletim->save());
        
//         $this->assertArrayHasKey('imoveis', $boletim->errors);
        
//         $boletim->imoveis = array(
//             array(
//                 'rua' => 'Rio de Janeiro',
//                 'numero' => '176',
//                 'seq' => null,
//                 'complemento' => 'AP 705',
//                 'imovel_lira' => false,
//                 'imovel_tipo' => 1,
//             ),
//             array(
//                 'rua' => 'Rio de Janeiro',
//                 'numero' => '176',
//                 'seq' => null,
//                 'complemento' => 'AP 704',
//                 'imovel_lira' => false,
//                 'imovel_tipo' => 1,
//             ),
//             array(
//                 'rua' => 'Rio de Janeiro',
//                 'numero' => '176',
//                 'seq' => null,
//                 'complemento' => 'AP 703',
//                 'imovel_lira' => false,
//                 'imovel_tipo' => 1,
//             ),
//             array(
//                 'rua' => 'Rio de Janeiro',
//                 'numero' => '173',
//                 'seq' => null,
//                 'complemento' => null,
//                 'imovel_lira' => true,
//                 'imovel_tipo' => 2,
//             ),
//         );
        
//         $this->assertTrue($boletim->save());
//         $this->assertEquals(4, $boletim->quantidadeImoveis);
        
//         $qtdeImoveisFechamento = BoletimRgFechamento::find()->doBoletim($boletim->id)->doTipoDeImovel(1)->doTipoLira(false)->count();
//         $this->assertEquals(1, $qtdeImoveisFechamento);
        
//         $imovelFechamento = BoletimRgFechamento::find()->doBoletim($boletim->id)->doTipoDeImovel(1)->doTipoLira(false)->one();
//         $this->assertEquals(3, $imovelFechamento->quantidade);
     
//         $qtdeImoveisFechamento = BoletimRgFechamento::find()->doBoletim($boletim->id)->doTipoDeImovel(1)->doTipoLira(true)->count();
//         $this->assertEquals(0, $qtdeImoveisFechamento);
        
//         $qtdeImoveisFechamento = BoletimRgFechamento::find()->doBoletim($boletim->id)->doTipoDeImovel(2)->doTipoLira(true)->count();
//         $this->assertEquals(1, $qtdeImoveisFechamento);
        
//         $imovelFechamento = BoletimRgFechamento::find()->doBoletim($boletim->id)->doTipoDeImovel(2)->doTipoLira(true)->one();
//         $this->assertEquals(1, $imovelFechamento->quantidade);
        
//         $qtdeImoveisFechamento = BoletimRgFechamento::find()->doBoletim($boletim->id)->doTipoDeImovel(2)->doTipoLira(false)->count();
//         $this->assertEquals(0, $qtdeImoveisFechamento);
        
//         $rua = BairroRua::find()->doBairro($bairro->id)->daRua('Rio de Janeiro')->one();
//         $this->assertInstanceOf('app\models\BairroRua', $rua);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(176)->doComplemento('AP 705')->doTipoLira(false)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(176)->doComplemento('AP 704')->doTipoLira(false)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(176)->doComplemento('AP 703')->doTipoLira(false)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(173)->doTipoLira(false)->one();
//         $this->assertNull($imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(173)->doTipoLira(true)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $this->boletimA = $boletim->id;
        
//         /*
//          * boletim 2
//          */
//         $bairro = Bairro::find(1);
//         $this->assertInstanceOf('app\models\Bairro', $bairro);
        
//         $boletim = new BoletimRg;

//         $this->assertFalse($boletim->validate());

//         $this->assertEquals(6, count($boletim->errors));
        
//         $this->assertArrayHasKey('folha', $boletim->errors);
//         $this->assertArrayHasKey('bairro_id', $boletim->errors);
//         $this->assertArrayHasKey('municipio_id', $boletim->errors);
//         $this->assertArrayHasKey('bairro_quarteirao_id', $boletim->errors);
//         $this->assertArrayHasKey('imoveis', $boletim->errors);
//         $this->assertArrayHasKey('data', $boletim->errors);
        
//         $boletim->folha = '001';
//         $boletim->municipio_id = 1;
//         $boletim->inserido_por = 1;
//         $boletim->bairro_id = $bairro->id;
//         $boletim->bairro_quarteirao_id = 180;
//         $boletim->data = date('d/m/Y');
//         $boletim->inserido_por = 1;
//         $boletim->categoria_id = $bairro->bairro_categoria_id;

//         $this->assertFalse($boletim->save());
        
//         $this->assertArrayHasKey('imoveis', $boletim->errors);
        
//         $boletim->imoveis = array(
//             array(
//                 'rua' => 'Vitorio Cella',
//                 'numero' => '176',
//                 'seq' => null,
//                 'complemento' => 'AP 401',
//                 'imovel_lira' => false,
//                 'imovel_tipo' => 1,
//             ),
//             array(
//                 'rua' => 'Vitorio Cella',
//                 'numero' => '176',
//                 'seq' => null,
//                 'complemento' => 'AP 402',
//                 'imovel_lira' => false,
//                 'imovel_tipo' => 1,
//             ),
//             array(
//                 'rua' => 'Vitorio Cella',
//                 'numero' => '176',
//                 'seq' => null,
//                 'complemento' => 'AP 403',
//                 'imovel_lira' => false,
//                 'imovel_tipo' => 1,
//             ),
//             array(
//                 'rua' => 'Vitorio Cella',
//                 'numero' => '176',
//                 'seq' => null,
//                 'complemento' => 'AP 404',
//                 'imovel_lira' => false,
//                 'imovel_tipo' => 1,
//             ),
//             array(
//                 'rua' => 'Vitorio Cella',
//                 'numero' => '176',
//                 'seq' => null,
//                 'complemento' => 'AP 405',
//                 'imovel_lira' => false,
//                 'imovel_tipo' => 1,
//             ),
//             array(
//                 'rua' => 'Vitorio Cella',
//                 'numero' => '178',
//                 'seq' => null,
//                 'complemento' => null,
//                 'imovel_lira' => true,
//                 'imovel_tipo' => 3,
//             ),
//            array(
//                 'rua' => 'Vitorio Cella',
//                 'numero' => '180',
//                 'seq' => null,
//                 'complemento' => null,
//                 'imovel_lira' => true,
//                 'imovel_tipo' => 2,
//             ),
//             array(
//                 'rua' => 'Vitorio Cella',
//                 'numero' => '182',
//                 'seq' => null,
//                 'complemento' => null,
//                 'imovel_lira' => true,
//                 'imovel_tipo' => 2,
//             ),
//         );
        
//         $this->assertFalse($boletim->save());
        
//         $this->assertArrayHasKey('folha', $boletim->errors);
        
//         $boletim->folha = '002';
        
//         $this->assertTrue($boletim->save());
//         $this->assertEquals(8, $boletim->quantidadeImoveis);
        
//         $qtdeImoveisFechamento = BoletimRgFechamento::find()->doBoletim($boletim->id)->doTipoDeImovel(1)->doTipoLira(false)->count();
//         $this->assertEquals(1, $qtdeImoveisFechamento);
        
//         $imovelFechamento = BoletimRgFechamento::find()->doBoletim($boletim->id)->doTipoDeImovel(1)->doTipoLira(false)->one();
//         $this->assertEquals(5, $imovelFechamento->quantidade);
     
//         $qtdeImoveisFechamento = BoletimRgFechamento::find()->doBoletim($boletim->id)->doTipoDeImovel(1)->doTipoLira(true)->count();
//         $this->assertEquals(0, $qtdeImoveisFechamento);
        
//         $qtdeImoveisFechamento = BoletimRgFechamento::find()->doBoletim($boletim->id)->doTipoDeImovel(2)->doTipoLira(true)->count();
//         $this->assertEquals(1, $qtdeImoveisFechamento);
        
//         $imovelFechamento = BoletimRgFechamento::find()->doBoletim($boletim->id)->doTipoDeImovel(2)->doTipoLira(true)->one();
//         $this->assertEquals(2, $imovelFechamento->quantidade);
        
//         $qtdeImoveisFechamento = BoletimRgFechamento::find()->doBoletim($boletim->id)->doTipoDeImovel(2)->doTipoLira(false)->count();
//         $this->assertEquals(0, $qtdeImoveisFechamento);
        
//         $qtdeImoveisFechamento = BoletimRgFechamento::find()->doBoletim($boletim->id)->doTipoDeImovel(3)->doTipoLira(true)->count();
//         $this->assertEquals(1, $qtdeImoveisFechamento);
        
//         $imovelFechamento = BoletimRgFechamento::find()->doBoletim($boletim->id)->doTipoDeImovel(3)->doTipoLira(true)->one();
//         $this->assertEquals(1, $imovelFechamento->quantidade);
        
//         $qtdeImoveisFechamento = BoletimRgFechamento::find()->doBoletim($boletim->id)->doTipoDeImovel(3)->doTipoLira(false)->count();
//         $this->assertEquals(0, $qtdeImoveisFechamento);
        
//         $rua = BairroRua::find()->doBairro($bairro->id)->daRua('Vitorio Cella')->one();
//         $this->assertInstanceOf('app\models\BairroRua', $rua);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(176)->doComplemento('AP 401')->doTipoLira(false)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(176)->doComplemento('AP 402')->doTipoLira(false)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(176)->doComplemento('AP 403')->doTipoLira(false)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(176)->doComplemento('AP 404')->doTipoLira(false)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(176)->doComplemento('AP 405')->doTipoLira(false)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(178)->doTipoLira(false)->one();
//         $this->assertNull($imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(178)->doTipoLira(true)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(180)->doTipoLira(true)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(182)->doTipoLira(true)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);

//         $this->boletimB = $boletim->id;
        
//         /*
//          * Update model 2
//          */
//         $boletimUpdate = BoletimRg::find($boletim->id);
//         $this->assertInstanceOf('app\models\BoletimRg', $boletimUpdate);

//         $boletimUpdate->folha = '001';

//         $this->assertFalse($boletimUpdate->save());

//         $this->assertArrayHasKey('imoveis', $boletimUpdate->errors);
        
//         $this->assertArrayHasKey('folha', $boletimUpdate->errors);
        
//         $boletimUpdate->folha = '002';
        
//         $boletimUpdate->imoveis = array(
//             array(
//                 'rua' => 'Vitorio Cella',
//                 'numero' => '176',
//                 'seq' => null,
//                 'complemento' => 'AP 401',
//                 'imovel_lira' => false,
//                 'imovel_tipo' => 1,
//             ),
//             array(
//                 'rua' => 'Vitorio Cella',
//                 'numero' => '178',
//                 'seq' => null,
//                 'complemento' => null,
//                 'imovel_lira' => true,
//                 'imovel_tipo' => 3,
//             ),
//            array(
//                 'rua' => 'Vitorio Cella',
//                 'numero' => '180',
//                 'seq' => null,
//                 'complemento' => null,
//                 'imovel_lira' => true,
//                 'imovel_tipo' => 2,
//             ),
//             array(
//                 'rua' => 'Vitorio Cella',
//                 'numero' => '182',
//                 'seq' => null,
//                 'complemento' => null,
//                 'imovel_lira' => true,
//                 'imovel_tipo' => 2,
//             ),
//         );
        
//         $this->assertTrue($boletimUpdate->save());
        
//         $this->assertEquals(4, $boletimUpdate->quantidadeImoveis);
        
//         $qtdeImoveisFechamento = BoletimRgFechamento::find()->doBoletim($boletimUpdate->id)->doTipoDeImovel(1)->doTipoLira(false)->count();
//         $this->assertEquals(1, $qtdeImoveisFechamento);
        
//         $imovelFechamento = BoletimRgFechamento::find()->doBoletim($boletimUpdate->id)->doTipoDeImovel(1)->doTipoLira(false)->one();
//         $this->assertEquals(1, $imovelFechamento->quantidade);
     
//         $qtdeImoveisFechamento = BoletimRgFechamento::find()->doBoletim($boletimUpdate->id)->doTipoDeImovel(1)->doTipoLira(true)->count();
//         $this->assertEquals(0, $qtdeImoveisFechamento);
        
//         $qtdeImoveisFechamento = BoletimRgFechamento::find()->doBoletim($boletimUpdate->id)->doTipoDeImovel(2)->doTipoLira(true)->count();
//         $this->assertEquals(1, $qtdeImoveisFechamento);
        
//         $imovelFechamento = BoletimRgFechamento::find()->doBoletim($boletimUpdate->id)->doTipoDeImovel(2)->doTipoLira(true)->one();
//         $this->assertEquals(2, $imovelFechamento->quantidade);
        
//         $qtdeImoveisFechamento = BoletimRgFechamento::find()->doBoletim($boletimUpdate->id)->doTipoDeImovel(2)->doTipoLira(false)->count();
//         $this->assertEquals(0, $qtdeImoveisFechamento);
        
//         $qtdeImoveisFechamento = BoletimRgFechamento::find()->doBoletim($boletimUpdate->id)->doTipoDeImovel(3)->doTipoLira(true)->count();
//         $this->assertEquals(1, $qtdeImoveisFechamento);
        
//         $imovelFechamento = BoletimRgFechamento::find()->doBoletim($boletimUpdate->id)->doTipoDeImovel(3)->doTipoLira(true)->one();
//         $this->assertEquals(1, $imovelFechamento->quantidade);
        
//         $qtdeImoveisFechamento = BoletimRgFechamento::find()->doBoletim($boletimUpdate->id)->doTipoDeImovel(3)->doTipoLira(false)->count();
//         $this->assertEquals(0, $qtdeImoveisFechamento);
        
//         /*
//          * Test delete
//          */
        
//         //deleta o boletim
//         $boletimDelete = BoletimRg::find($boletim->id);
//         $this->assertInstanceOf('app\models\BoletimRg', $boletimDelete);
        
//         $this->assertEquals(1, $boletimDelete->delete());
        
//         $this->assertEquals(0, BoletimRgFechamento::find()->doBoletim($boletim->id)->count());
        
//         //mantem todas ruas e imoveis
//         $rua = BairroRua::find()->doBairro($bairro->id)->daRua('Vitorio Cella')->one();
//         $this->assertInstanceOf('app\models\BairroRua', $rua);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(176)->doComplemento('AP 401')->doTipoLira(false)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(176)->doComplemento('AP 402')->doTipoLira(false)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(176)->doComplemento('AP 403')->doTipoLira(false)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(176)->doComplemento('AP 404')->doTipoLira(false)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(176)->doComplemento('AP 405')->doTipoLira(false)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(178)->doTipoLira(false)->one();
//         $this->assertNull($imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(178)->doTipoLira(true)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(180)->doTipoLira(true)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(182)->doTipoLira(true)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         //mantem tudo do primeiro boletim
//         unset($boletim);
//         $boletimPrimeiro = BoletimRg::find(1);
//         $this->assertInstanceOf('app\models\BoletimRg', $boletimPrimeiro);
        
//         $this->assertEquals(4, $boletimPrimeiro->quantidadeImoveis);
        
//         $qtdeImoveisFechamento = BoletimRgFechamento::find()->doBoletim($boletimPrimeiro->id)->doTipoDeImovel(1)->doTipoLira(false)->count();
//         $this->assertEquals(1, $qtdeImoveisFechamento);
        
//         $imovelFechamento = BoletimRgFechamento::find()->doBoletim($boletimPrimeiro->id)->doTipoDeImovel(1)->doTipoLira(false)->one();
//         $this->assertEquals(3, $imovelFechamento->quantidade);
     
//         $qtdeImoveisFechamento = BoletimRgFechamento::find()->doBoletim($boletimPrimeiro->id)->doTipoDeImovel(1)->doTipoLira(true)->count();
//         $this->assertEquals(0, $qtdeImoveisFechamento);
        
//         $qtdeImoveisFechamento = BoletimRgFechamento::find()->doBoletim($boletimPrimeiro->id)->doTipoDeImovel(2)->doTipoLira(true)->count();
//         $this->assertEquals(1, $qtdeImoveisFechamento);
        
//         $imovelFechamento = BoletimRgFechamento::find()->doBoletim($boletimPrimeiro->id)->doTipoDeImovel(2)->doTipoLira(true)->one();
//         $this->assertEquals(1, $imovelFechamento->quantidade);
        
//         $qtdeImoveisFechamento = BoletimRgFechamento::find()->doBoletim($boletimPrimeiro->id)->doTipoDeImovel(2)->doTipoLira(false)->count();
//         $this->assertEquals(0, $qtdeImoveisFechamento);
        
//         $rua = BairroRua::find()->doBairro($bairro->id)->daRua('Rio de Janeiro')->one();
//         $this->assertInstanceOf('app\models\BairroRua', $rua);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(176)->doComplemento('AP 705')->doTipoLira(false)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(176)->doComplemento('AP 704')->doTipoLira(false)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(176)->doComplemento('AP 703')->doTipoLira(false)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(173)->doTipoLira(false)->one();
//         $this->assertNull($imovel);
        
//         $imovel = BairroRuaImovel::find()->daRua($rua->id)->doNumero(173)->doTipoLira(true)->one();
//         $this->assertInstanceOf('app\models\BairroRuaImovel', $imovel);
        
//         $this->assertEquals(1, $this->boletimA);
//         $this->assertEquals('asd', $this->boletimB);
//     }
    
    
//     public function testVars() {
//         $this->assertEquals(1, $this->boletimA);
//         $this->assertEquals('asd', $this->boletimB);
//     }
// }
