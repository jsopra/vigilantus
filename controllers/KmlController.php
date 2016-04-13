<?php

namespace app\controllers;

use app\components\Controller;
use app\models\Cliente;
use app\models\Bairro;
use app\models\BairroQuarteirao;
use app\models\FocoTransmissor;
use app\models\Armadilha;
use app\models\PontoEstrategico;
use app\models\Ocorrencia;
use app\models\CasoDoenca;
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
                'only' => ['cidade', 'bairro', 'area-tratamento-foco', 'armadilha', 'ponto-estrategico', 'ocorrencias', 'casos-doenca'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['area-tratamento-foco', 'cidade', 'bairro', 'armadilha', 'ponto-estrategico', 'ocorrencias', 'casos-doenca'],
                        'roles' => ['Gerente', 'Analista'],
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

    public function actionFocos($clienteId = null, $especieId = null, $bairroId = null, $lira = null, $informacaoPublica = null, $inicio = null, $fim = null)
    {
        $cliente = $clienteId ? Cliente::find()->andWhere(['id' => $clienteId])->one() : \Yii::$app->user->identity->cliente;
        if(!$cliente) {
            exit;
        }

        $cacheName = 'focos' . implode(',', [
            $clienteId,
            $especieId,
            $bairroId,
            $lira,
            $informacaoPublica,
            $inicio,
            $fim
        ]);
        $data = Yii::$app->cache->get($cacheName);

        if ($data === false || $data === null) {

            $model = new Kml;

            $modelFocos = FocoTransmissorRedis::find();

            $modelFocos->doCliente($cliente->id);

            if (is_numeric($bairroId)) {
                $modelFocos->doBairro($bairroId);
            }

            if ($lira == '1' || $lira == '0') {
                $modelFocos->doImovelLira(($lira ? true : false));
            }

            if (is_numeric($especieId)) {
                $modelFocos->daEspecieDeTransmissor($especieId);
            }

            if ($informacaoPublica == '1') {
                $modelFocos->informacaoPublica();
            }

            if ($inicio && $fim) {
                $modelFocos->dataEntradaEntre($inicio, $fim);
            }

            $focos = $modelFocos->all();
            foreach ($focos as $foco) {

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

            $data = $model;

            Yii::$app->cache->set($cacheName, serialize($data), (60 * 60)); //1h de cache

        } else {
            $data = unserialize($data);
        }

        return $data->output();
    }

    public function actionArmadilha($except = null)
    {
        $model = new Kml;
        $model->id = 'armadilha';

        $armadilhas = $except ? Armadilha::find()->queNao($except)->all() : Armadilha::find()->all();
        foreach($armadilhas as $armadilha) {

            $armadilha->loadCoordenadas();

            $point = new Point;
            $point->value = [$armadilha->longitude, $armadilha->latitude];

            $point->extendedData = [
                'numero_quarteirao' => $armadilha->bairro_quarteirao_id ? $armadilha->bairroQuarteirao->numero_quarteirao : null,
                'bairro' => $armadilha->bairro_quarteirao_id ? $armadilha->bairroQuarteirao->bairro->nome : null,
            ];

            $model->add($point);
            unset($point);
        }

        return $model->output();
    }

    public function actionPontoEstrategico($except = null)
    {
        $model = new Kml;
        $model->id = 'pontoestrategico';

        $pontos = $except ? PontoEstrategico::find()->queNao($except)->all() : PontoEstrategico::find()->all();
        foreach($pontos as $ponto) {

            $ponto->loadCoordenadas();

            $point = new Point;
            $point->value = [$ponto->longitude, $ponto->latitude];

            $point->extendedData = [
                'numero_quarteirao' => $ponto->bairro_quarteirao_id ? $ponto->bairroQuarteirao->numero_quarteirao : null,
                'bairro' => $ponto->bairro_quarteirao_id ? $ponto->bairroQuarteirao->bairro->nome : null,
            ];

            $model->add($point);
            unset($point);
        }

        return $model->output();
    }

    public function actionOcorrencias($except = null)
    {
        $model = new Kml;
        $model->id = 'ocorrencia';

        $pontos = $except ? Ocorrencia::find()->queNao($except)->all() : Ocorrencia::find()->all();
        foreach($pontos as $ponto) {

            if(!$ponto->bairro_quarteirao_id) {
                continue;
            }

            $quarteirao = $ponto->bairroQuarteirao;
            $quarteirao->loadCoordenadas();

            $point = new Point;
            $point->value = $quarteirao->getCentro();

            $point->extendedData = [
                'numero_quarteirao' => $ponto->bairro_quarteirao_id ? $quarteirao->numero_quarteirao : null,
                'bairro' => $ponto->bairro_quarteirao_id ? $quarteirao->bairro->nome : null,
            ];

            $model->add($point);
            unset($point);
        }

        return $model->output();
    }

    public function actionCasosDoenca()
    {
        $model = new Kml;
        $model->id = 'casosdoenca';

        $pontos = CasoDoenca::find()->all();
        foreach($pontos as $ponto) {

            if(!$ponto->bairro_quarteirao_id) {
                continue;
            }

            $quarteirao = $ponto->bairroQuarteirao;
            $quarteirao->loadCoordenadas();

            $point = new Point;
            $point->value = $quarteirao->getCentro();

            $point->extendedData = [
                'numero_quarteirao' => $ponto->bairro_quarteirao_id ? $quarteirao->numero_quarteirao : null,
                'bairro' => $ponto->bairro_quarteirao_id ? $quarteirao->bairro->nome : null,
                'nome_paciente' => $ponto->nome_paciente,
                'data_sintomas' => $ponto->getFormattedAttribute('data_sintomas'),
            ];

            $model->add($point);
            unset($point);
        }

        return $model->output();
    }
}
