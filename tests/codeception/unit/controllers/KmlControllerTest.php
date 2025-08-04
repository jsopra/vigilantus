<?php
namespace tests\unit\controllers;

use Yii;
use app\controllers\KmlController;
use yii\web\HttpException;

class KmlControllerTest extends \Codeception\Test\Unit
{
    public function testActionFocosSemCliente()
    {
        $controller = new KmlController('kml', Yii::$app);

        try {
            $controller->actionFocos(null, null, null, null, null, null, null);
            $this->fail('HttpException não lançada');
        } catch (HttpException $e) {
            $this->assertEquals(404, $e->statusCode);
        }

        $this->assertTrue(true, 'Execução continuou após tratamento de erro');
    }
}
