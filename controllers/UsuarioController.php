<?php

namespace app\controllers;

use app\components\CRUDController;
use app\forms\AlterarSenhaForm;
use Yii;

class UsuarioController extends CRUDController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access']['only'][] = 'change-password';
        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['change-password'],
            'roles' => ['@'],
        ];
        return $behaviors;
    }
    
    public function actionChangePassword()
    {
        $model = new AlterarSenhaForm;
        
        if ($model->load($_POST) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Senha alterada com sucesso.');
            return $this->redirect(['site/home']);
        }
        
        return $this->render('change-password', ['model' => $model]);
    }
    
    /**
	 * @inheritdoc
	 */
    protected function findModel($id)
    {
        $model = parent::findModel($id);
        
        // Remove a senha da edição
        $model->senha = null;
        
        return $model;
    }
    
    /**
     * @return \app\components\ActiveRecord
     */
    protected function buildNewModel()
    {
        $class = $this->getModelClassName();
        $model = new $class;
        $model->municipio_id = Yii::$app->user->identity->municipio_id;
        return $model;
    }
}
