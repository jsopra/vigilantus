<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>

class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass."\n"; ?>
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
				'actions'=>array('index','view','create','update', 'ajaxSave','delete', 'ajaxDelete'),
				'users'=>array('@'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Exibe um modelo.
	 * @param integer $id o ID do modelo a ser exibido
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Cria um novo modelo.
	 * Se a criação ocorrer com sucesso, redireciona pra action 'view'.
	 */
	public function actionCreate()
	{
		$model=new <?php echo $this->modelClass; ?>;

		// Descomente essa linha se a validação via AJAX for necessária
		// $this->performAjaxValidation($model);

		if(isset($_POST['<?php echo $this->modelClass; ?>']))
		{
			$model->attributes=$_POST['<?php echo $this->modelClass; ?>'];
			if($model->save())
				$this->redirect(array('view','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>));
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

		// Descomente essa linha se a validação via AJAX for necessária
		// $this->performAjaxValidation($model);

		if(isset($_POST['<?php echo $this->modelClass; ?>']))
		{
			$model->attributes=$_POST['<?php echo $this->modelClass; ?>'];
			if($model->save())
				$this->redirect(array('view','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>));
		}

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
		$model=new <?php echo $this->modelClass; ?>('search');
		$model->unsetAttributes();  // limpa quaisquer valores padrão
		if(isset($_GET['<?php echo $this->modelClass; ?>']))
			$model->attributes=$_GET['<?php echo $this->modelClass; ?>'];

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
	   if (Yii::app()->request->isPostRequest && isset($_POST['<?php echo $this->modelClass; ?>'])) {

		   $model = $id ? $this->loadModel($id) : new <?php echo $this->modelClass; ?>;

		   $model->attributes = $_POST['<?php echo $this->modelClass; ?>'];

		   $data = ($model->save() ? array('saved' => true) : array('saved' => false, 'errors' => $model->getErrors()));

		   echo CJSON::encode($data);
	   }
	   else
		   throw new CHttpException(400);
	}

	/**
	 * Exclui um ou mais modelos.
	 * @param integer $ids os IDs dos modelos a serem excluído
	 */
	public function actionAjaxDelete()
	{
		if (Yii::app()->request->isPostRequest) {
			
			$ids = (array) $_POST['ids'];
			
			foreach ($ids as $k => $id)
				$this->loadModel($id)->delete();
		}
		else
			throw new CHttpException(400,'Requisição inválida.');
	}

	/**
	 * Retorna os dados do modelo baseado na primary key dada na variável GET.
	 * Se os dados do modelo não forem encontrados, será lançada uma exceção HTTP.
	 * @param integer o ID do modelo a ser carregado
	 */
	public function loadModel($id)
	{
		$model=<?php echo $this->modelClass; ?>::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='<?php echo $this->class2id($this->modelClass); ?>-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
