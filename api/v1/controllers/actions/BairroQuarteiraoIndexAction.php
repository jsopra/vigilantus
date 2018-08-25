<?php
namespace api\v1\controllers\actions;

use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\Action;
use app\models\EquipeAgente;

class BairroQuarteiraoIndexAction extends Action
{
    public $prepareDataProvider;

    /**
     * @return ActiveDataProvider
     */
    public function run($comVisita = false)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        return $this->prepareDataProvider($comVisita);
    }

    /**
     * Prepares the data provider that should return the requested collection of the models.
     * @return ActiveDataProvider
     */
    protected function prepareDataProvider($comVisita = null)
    {
        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this);
        }

        $modelClass = $this->modelClass;

        $query = $modelClass::find();

        if ($comVisita) {
            $user = Yii::$app->user->identity;
            $agente = EquipeAgente::find()->doUsuario($user->id)->one();
            if($agente instanceof EquipeAgente) {

                $query->comVisita($agente);
            }
        }

        return Yii::createObject([
            'class' => ActiveDataProvider::className(),
            'query' => $query
        ]);
    }
}
