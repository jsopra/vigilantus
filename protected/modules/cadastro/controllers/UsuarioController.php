<?php

class UsuarioController extends Controller
{

	/**
	 * @return array filtros das actions
	 */
	public function filters()
	{
		return array(
			'accessControl', // faz o controle de acesso nas operações CRUD
		);
	}

	/**
	 * Especifica as regras de controle de acesso.
	 * Este método é usado pelo filtro 'accessControl'.
	 *
	 * @return array regras de controle de acesso
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('index', 'create', 'update', 'delete'),
				'users'=>array('@'),
                'roles' => array('Administrador'),
			),
            array('allow',
				'actions'=>array('updatePassword'),
				'users' => array('@'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Cria um novo modelo.
	 * Se a criação ocorrer com sucesso, redireciona pra action 'view'.
	 */
	public function actionCreate()
	{
		$model=new Usuario('insert');

        if(!Yii::app()->user->isRoot())
            $model->municipio_id = Yii::app()->user->getUser()->municipio_id;
        
		if(isset($_POST['Usuario']))
		{
			$model->attributes=$_POST['Usuario'];
			if($model->save())
				$this->redirect(array('index'));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Atualiza um modelo.
	 * Se a atualização for feita com sucesso, redirecionará para a ação de 'view'.
	 * @param integer $id o ID do modelo a ser atualizado
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
        
        if(!Yii::app()->user->isRoot())
            $model->municipio_id = Yii::app()->user->getUser()->municipio_id;

		if(isset($_POST['Usuario']))
		{
			$model->attributes=$_POST['Usuario'];
            
			if($model->save())
				$this->redirect(array('index'));
		}

        $model->senha = null;
        
		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Exclui um modelo.
	 * Se a exclusão ocorrer com sucesso, redirecionará para a ação 'index'.
	 * @param integer $id o ID do modelo a ser excluído
	 */
	public function actionDelete($id)
	{
		if (Yii::app()->request->isPostRequest) {
		
			// a exclusão só é permitida via requisição POST
			$this->loadModel($id)->delete();

			// se for uma requisição AJAX (disparada por um grid view), não deve redirecionar
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
		else
			throw new CHttpException(400,'Requisição inválida.');
	}

	/**
	 * Lista todos os modelos.
	 */
	public function actionIndex()
	{
		$model=new Usuario('search');
		$model->unsetAttributes();  // limpa quaisquer valores padrão
		if(isset($_GET['Usuario']))
			$model->attributes=$_GET['Usuario'];

		$this->render('index',array(
			'model'=>$model,
		));
	}
    
    public function actionUpdatePassword() {
		
		$model=$this->loadModel(Yii::app()->user->id);
		$model->scenario = 'updatePassword';
		$model->senha = null;
		
		if(isset($_POST['Usuario']))
		{
			$model->attributes=$_POST['Usuario'];
			
			if($model->save()) {
				Yii::app()->user->setFlash('success', Yii::t('Usuario', 'Senha alterada com suceso'));
				$this->redirect(Yii::app()->user->returnUrl);
			}
		}

		$this->render('updatePassword',array(
			'model'=>$model,
		));
	}

	/**
	 * Retorna os dados do modelo baseado na primary key dada na variável GET.
	 * Se os dados do modelo não forem encontrados, será lançada uma exceção HTTP.
	 * @param integer o ID do modelo a ser carregado
	 */
	public function loadModel($id)
	{
		$model=Usuario::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'A página solicitada não existe.');
		return $model;
	}

	/**
	 * Faz a validação AJAX.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='usuario-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
