<?php

class BairroController extends Controller
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
				'actions'=>array('index','ajaxSave','delete'),
				'users'=>array('@'),
                'roles' => array('Administrador'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
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
		$model=new Bairro('search');
		$model->unsetAttributes();  // limpa quaisquer valores padrão
		if(isset($_GET['Bairro']))
			$model->attributes=$_GET['Bairro'];

		$this->render('index',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Salva os dados postados via AJAX e retorna um JSON
	 * 
	 * @param integer $id Exceto se for um novo registro
	 */
	public function actionAjaxSave($id = null)
	{
	   if (Yii::app()->request->isPostRequest && isset($_POST['Bairro'])) {

		   $model = $id ? $this->loadModel($id) : new Bairro;

		   $model->attributes = $_POST['Bairro'];

		   $data = ($model->save() ? array('saved' => true) : array('saved' => false, 'errors' => $model->getErrors()));

		   echo CJSON::encode($data);
	   }
	   else
		   throw new CHttpException(400);
	}

	/**
	 * Retorna os dados do modelo baseado na primary key dada na variável GET.
	 * Se os dados do modelo não forem encontrados, será lançada uma exceção HTTP.
	 * @param integer o ID do modelo a ser carregado
	 */
	public function loadModel($id)
	{
		$model=Bairro::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='bairro-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
