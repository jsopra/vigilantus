<?php
namespace api\rest;

use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\web\Response;

class ActiveController extends \yii\rest\ActiveController
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
                'Access-Control-Request-Headers' => ['Accept', 'Authorization', 'Content-Type', 'WWW-Authenticate'],
                'Access-Control-Request-Method' => ['GET', 'HEAD', 'POST', 'OPTIONS'],
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
}
