<?php
namespace api\v1\controllers\actions;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\ServerErrorHttpException;
use api\forms\ExecucaoVisitaForm;
use yii\rest\Action;

class SemanaEpidemiologicaVisitaUpdateAction extends Action
{
    /**
     * @var string the scenario to be assigned to the model before it is validated and updated.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * Updates an existing model.
     * @param string $id the primary key of the model.
     * @return \yii\db\ActiveRecordInterface the model being updated
     * @throws ServerErrorHttpException if there is any error when updating the model
     */
    public function run($id)
    {
        /* @var $model ActiveRecord */
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }
        $request = Yii::$app->request;

        //$model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $form = new ExecucaoVisitaForm;
        $form->visita = $model->id;
        $form->usuario_id = Yii::$app->user->identity->id;
        $form->visita_status_id = $request->post('visita_status_id');
        $form->imoveis = $request->post('imoveis');
        $form->data_atividade = $request->post('data_atividade');
die(var_dump($form));
        if ($form->save() === false && !$form->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }

        return $form;
    }
}
