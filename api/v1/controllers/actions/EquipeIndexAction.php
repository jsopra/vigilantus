<?php
namespace api\v1\controllers\actions;

use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\Action;
use app\models\EquipeAgente;

class EquipeIndexAction extends Action
{
    public $prepareDataProvider;

    /**
     * @return ActiveDataProvider
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        return $this->prepareDataProvider();
    }

    /**
     * Prepares the data provider that should return the requested collection of the models.
     * @return ActiveDataProvider
     */
    protected function prepareDataProvider()
    {
        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this);
        }

        $modelClass = $this->modelClass;

        $user = Yii::$app->user->identity;
        $agente = EquipeAgente::find()->doUsuario($user->id)->one();
        if ($agente instanceof EquipeAgente) {
            //filtra
            die(va_dump($agente));
        }

        return Yii::createObject([
            'class' => ActiveDataProvider::className(),
            'query' => $modelClass::find(),
        ]);
    }
}
