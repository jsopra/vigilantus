<?php

namespace app\controllers;

use app\components\CRUDController;
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
        $model = $this->findModel(Yii::$app->user->id);
        
        if (Yii::$app->request->isPost && isset($_POST['Usuario'])) {
            
            $model->senha = $_POST['Usuario']['senha'];
            $model->senha2 = $_POST['Usuario']['senha2'];
            
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Senha alterada com sucesso');
                return $this->goHome();
            }
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
}
