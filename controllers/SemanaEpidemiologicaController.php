<?php

namespace app\controllers;

use Yii;
use app\components\CRUDController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\SemanaEpidemiologicaVisita;
use app\models\Visita;
use app\models\EquipeAgente;
use app\models\search\EquipeAgenteSearch;
use app\models\search\SemanaEpidemiologicaVisitaSearch;
use app\forms\SemanaEpidemiologicaVisitaAgendamentoForm;
use app\models\report\ResumoTrabalhoCampoReport;
use yii\web\NotFoundHttpException;

class SemanaEpidemiologicaController extends CRUDController
{

    public function actions()
    {
        return [
            'bairroQuarteiroes' => ['class' => 'app\components\actions\BairroQuarteiroes'],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'delete', 'index', 'update'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete', 'index', 'visitas', 'agendar', 'agentes', 'bairroQuarteiroes', 'deleteVisita', 'mapa', 'resumo'],
                        'roles' => ['Usuario', 'Supervisor'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionAgentes($cicloId)
    {
        $ciclo = $this->findModel($cicloId);
        
        $searchModel = new EquipeAgenteSearch();
        $dataProvider = $searchModel->search($_GET);

        return $this->renderAjaxOrLayout(
            'agentes',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'ciclo' => $ciclo
            ]
        );
    }

    public function actionVisitas($cicloId, $agenteId)
    {
        $ciclo = $this->findModel($cicloId);
        if (($agente = EquipeAgente::findOne(intval($agenteId))) === null) {
           throw new NotFoundHttpException('The requested page does not exist.');
        }
        
        $searchModel = new SemanaEpidemiologicaVisitaSearch();
        $_GET['SemanaEpidemiologicaVisitaSearch']['agente_id'] = $agente->id;
        $_GET['SemanaEpidemiologicaVisitaSearch']['semana_epidemiologica_id'] = $ciclo->id;
        $dataProvider = $searchModel->search($_GET);

        return $this->renderAjaxOrLayout(
            'visitas',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'ciclo' => $ciclo,
                'agente' => $agente
            ]
        );
    }

    public function actionAgendar($cicloId, $agenteId)
    {
        $ciclo = $this->findModel($cicloId);
        if (($agente = EquipeAgente::findOne(intval($agenteId))) === null) {
           throw new NotFoundHttpException('The requested page does not exist.');
        }

        $model = new SemanaEpidemiologicaVisitaAgendamentoForm;
        $model->semana_epidemiologica_id = $ciclo->id;
        $model->agente_id = $agente->id;
        $model->usuario_id = Yii::$app->user->identity->id;

        if ($model->load($_POST) && $model->validate() && $model->save()) {
            $this->redirect(['semana-epidemiologica/visitas', 'cicloId' => $ciclo->id, 'agenteId' => $agente->id]);
        }

        return $this->renderAjaxOrLayout(
            'agendar',
            [
                'model' => $model,
                'ciclo' => $ciclo,
                'agente' => $agente
            ]
        );
    }

    public function actionDeleteVisita($cicloId, $agenteId, $visitaId)
    {
        $ciclo = $this->findModel($cicloId);
        if (($agente = EquipeAgente::findOne(intval($agenteId))) === null) {
           throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (($model = SemanaEpidemiologicaVisita::findOne(intval($visitaId))) === null) {
           throw new NotFoundHttpException('The requested page does not exist.');
        }

        $this->disableOrDelete($model);

        if (!Yii::$app->request->isAjax) {
            Yii::$app->session->setFlash('success', 'O registro foi excluído com sucesso.');
        }

        return $this->redirect(['semana-epidemiologica/visitas', 'cicloId' => $ciclo->id, 'agenteId' => $agente->id]);
    }

    public function actionMapa($cicloId, $agenteId)
    {
        $ciclo = $this->findModel($cicloId);
        if (($agente = EquipeAgente::findOne(intval($agenteId))) === null) {
           throw new NotFoundHttpException('The requested page does not exist.');
        }
        return $this->renderAjaxOrLayout(
            'mapa',
            [
                'ciclo' => $ciclo,
                'agente' => $agente
            ]
        );
    }

    public function actionResumo($cicloId, $agenteId)
    {
        $ciclo = $this->findModel($cicloId);
        if (($agente = EquipeAgente::findOne(intval($agenteId))) === null) {
           throw new NotFoundHttpException('The requested page does not exist.');
        }

        $report = new ResumoTrabalhoCampoReport;
        $report->agente_id = $agente->id;
        $report->semana_id = $ciclo->id;

        return $this->renderAjaxOrLayout(
            'resumo',
            [
                'ciclo' => $ciclo,
                'agente' => $agente,
                'data' => $report->getData(),
            ]
        );
    }
}
