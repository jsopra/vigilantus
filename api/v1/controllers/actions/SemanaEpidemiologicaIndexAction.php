<?php
namespace api\v1\controllers\actions;

use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\Action;
use app\models\EquipeAgente;

class SemanaEpidemiologicaIndexAction extends Action
{
    public $prepareDataProvider;

    /**
     * @return ActiveDataProvider
     */
    public function run($somenteAtual = null)
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
    protected function prepareDataProvider($somenteAtual = null)
    {
        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this);
        }

        $modelClass = $this->modelClass;

        $query = $modelClass::find();

        $user = Yii::$app->user->identity;
        $agente = EquipeAgente::find()->doUsuario($user->id)->one();
        if ($agente instanceof EquipeAgente) {
            $query->doAgente($agente->id);
        } else {
            return [];
        }

        if ($somenteAtual) {
            $query->atual();
        }

        return Yii::createObject([
            'class' => ActiveDataProvider::className(),
            'query' => $query
        ]);
    }
}
