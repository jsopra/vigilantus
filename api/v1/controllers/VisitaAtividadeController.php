<?php
namespace api\v1\controllers;

use api\models\VisitaAtividade;
use Yii;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\rest\Controller;
use yii\web\Response;

class VisitaAtividadeController extends Controller
{
    /**
     * Configura:
     * 1. Resposta no formato JSON
     * 2. Resolve bug de CORS
     * 3. Configura a autenticação via HttpBearer
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // É obrigatório remover isso ao invés de sobrescrever
        unset($behaviors['authenticator']);

        // Responde como JSON ao invés de XML
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        // É *ESSENCIAL* que `cors` venha antes de `authenticator` no array!!!
        $behaviors['cors'] = [
            'class' => Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Expose-Headers' => [
                    'Link',
                    'X-Pagination-Current-Page',
                    'X-Pagination-Page-Count',
                    'X-Pagination-Per-Page',
                    'X-Pagination-Total-Count',
                ],
            ],
        ];

        // Agora seta novamente a chave removida acima
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            // Resolve bug de autenticação x CORS
            'except' => ['options'],
        ];

        return $behaviors;
    }

    public function actionIndex()
    {
        return VisitaAtividade::getDescricoes();
    }

    public function actionOptions()
    {
        if (Yii::$app->getRequest()->getMethod() !== 'OPTIONS') {
            Yii::$app->getResponse()->setStatusCode(405);
        }
        $options = ['GET', 'OPTIONS'];
        Yii::$app->getResponse()->getHeaders()->set('Allow', implode(', ', $options));
    }
}
