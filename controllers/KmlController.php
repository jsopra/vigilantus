<?php

namespace app\controllers;

use app\components\Controller;
use app\models\Bairro;
use app\models\BairroQuarteirao;
use app\models\FocoTransmissor;
use app\models\redis\FocoTransmissor as FocoTransmissorRedis;
use Yii;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use perspectivain\geo\kml\Kml;
use perspectivain\geo\kml\models\Polygon;
use perspectivain\geo\kml\models\Point;

class KmlController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['focos', 'cidade', 'bairro', 'area-tratamento-foco'],
                        'roles' => ['Usuario'],
                    ],
                ],
            ],
        ];
    }

    public function actionCidade($except = null)
    {
        $model = new Kml;
        $model->id = 'bairro';

        $bairros = $except ? Bairro::find()->queNao($except)->all() : Bairro::find()->all();
        foreach($bairros as $bairro) {

            $bairro->loadCoordenadas();

            $polygon = new Polygon;

            foreach($bairro->coordenadas as $coordenada) {
                $point = new Point;
                $point->value = $coordenada;
                $polygon->value[] = $point;
                unset($point);
            }

            $polygon->extendedData = [
                'nome' => $bairro->nome,
            ];

            $model->add($polygon);
            unset($polygon);
        }

        return $model->output();
    }

    public function actionBairro($id, $except = null)
    {
        $model = new Kml;
        $model->id = 'quarteirao';

        $bairro = Bairro::find()->where(['id' => $id])->one();
        if(!$bairro) {
            return false;
        }

        if($except) {
            $quarteiroes = BairroQuarteirao::find()->doBairro($bairro->id)->queNao($except)->all();
        }
        else {
            $quarteiroes = $bairro->quarteiroes;
        }

        foreach($quarteiroes as $quarteirao) {

            $quarteirao->loadCoordenadas();

            $polygon = new Polygon;

            foreach($quarteirao->coordenadas as $coordenada) {
                $point = new Point;
                $point->value = $coordenada;
                $polygon->value[] = $point;
                unset($point);
            }

            $polygon->extendedData = [
                'numero_quarteirao' => $quarteirao->numero_quarteirao,
            ];

            $model->add($polygon);
            unset($polygon);
        }

        return $model->output();
    }

    public function actionAreaTratamentoFoco($id)
    {
        $model = new Kml;
        $model->id = 'quarteirao';

        $foco = FocoTransmissor::find()->where(['id' => $id])->one();
        if(!$foco) {
            return false;
        }

        $areaTratamento = $foco->getAreaTratamento();
        foreach ($areaTratamento as $quarteirao) {

            $quarteirao->loadCoordenadas();

            $polygon = new Polygon;

            foreach($quarteirao->coordenadas as $coordenada) {

                $point = new Point;
                $point->value = $coordenada;
                $polygon->value[] = $point;
                unset($point);
            }

            $polygon->extendedData = [
                'numero_quarteirao' => $quarteirao->numero_quarteirao,
            ];

            $model->add($polygon);
            unset($polygon);
        }

        return $model->output();
    }

    public function actionFocos($especieId = null, $bairroId = null, $lira = null, $informacaoPublica = null)
    {
        $cliente = \Yii::$app->session->get('user.cliente');
        if(!$cliente) {
            exit;
        }

        $model = new Kml;

        $modelFocos = FocoTransmissorRedis::find();

        $modelFocos->doCliente($cliente->id);

        if(is_numeric($bairroId)) {
            $modelFocos->doBairro($bairroId);
        }

        if($lira == '1' || $lira == '0') {
            $modelFocos->doImovelLira(($lira ? true : false));
        }

        if(is_numeric($especieId)) {
            $modelFocos->daEspecieDeTransmissor($especieId);
        }

        if($informacaoPublica == '1') {
            $modelFocos->informacaoPublica();
        }

        $focos = $modelFocos->all();
        foreach($focos as $foco) {

            $quarteirao = $foco->bairroQuarteirao;
            $quarteirao->loadCoordenadas();

            $point = new Point;
            $point->value = $quarteirao->getCentro();

            $point->extendedData = [
                'metros_tratamento' => $foco->especieTransmissor->qtde_metros_area_foco,
                'numero_quarteirao' => $quarteirao->numero_quarteirao,
            ];

            $model->add($point);
            unset($point);
        }

        return $model->output();
    }
}
