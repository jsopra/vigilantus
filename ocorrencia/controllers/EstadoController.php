<?php
namespace app\ocorrencia\controllers;

use app\components\Controller;
use app\models\Estado;
use app\models\Municipio;
use yii\web\HttpException;

class EstadoController extends Controller
{
    public function actionIndex()
    {
        return $this->render(
            'index',
            ['query' => Estado::find()->orderBy('nome')]
        );
    }
}
