<?php
namespace tests\unit\components\actions;

use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use app\components\actions\BairroCategoria;

class BairroCategoriaActionTest extends \Codeception\Test\Unit
{
    public function testRunComParametroInvalido()
    {
        $controller = new Controller('test', Yii::$app);
        $action = new BairroCategoria('bairro-categoria', $controller);

        $_REQUEST['bairro_id'] = 'abc';

        try {
            $action->run();
            $this->fail('HttpException não lançada');
        } catch (HttpException $e) {
            $this->assertEquals(400, $e->statusCode);
        }

        unset($_REQUEST['bairro_id']);
        $this->assertTrue(true, 'Execução continuou após tratamento de erro');
    }
}
