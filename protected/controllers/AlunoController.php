<?php

class AlunoController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
					'actions'=>array('index','view'),
					'roles'=>array('admin', 'secretario', 'professor'),
			),
			array('allow', 
					'actions'=>array('update', 'create'),
					'roles'=>array('admin', 'secretario'),
			),
			array('allow',
					'actions'=>array('admin','delete'),
					'roles'=>array('admin'),
			),
			array('deny',  // deny all users
					'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 */
	public function actionView()
	{
		$this->render('view',array(
			'model'=>$this->loadModel(),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new aluno;
		$user=new user;
		$matricula=new matricula;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['aluno']) && isset($_POST['user']))
		{
			$model->attributes=$_POST['aluno'];
			$user->attributes=$_POST['user'];
			$matricula->attributes=$_POST['matricula'];
			$user->tipo='aluno';
			
			if($model->validate() && $user->validate()){
				
				$user->save(); // Primeiro salva o usuário para pegar o ID dele.
				
				$model->id = $user->id; // Pegando o ID do usu�rio cadastrado, e vinculado ao id_usuario da tabela Cliente
				$model->matricula='2014'.$matricula->curso.$user->id;
				if($model->save())//Salvando os dados do CLiente j� com o ID do Usu�rio
					$matricula->matricula=$model->matricula;
					$matricula->aluno=$model->id;
					$matricula->save();
					$this->redirect(array('view','id'=>$model->id));
			}
			
		}

		$this->render('create',array(
			'model'=>$model,
			'user'=>$user,
			'matricula'=>$matricula,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionUpdate()
	{
		$model=$this->loadModel();
		$user=user::model()->findByPk($model->id);
		$matricula=matricula::model()->findByPk($model->matricula0->id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['aluno']) and isset($_POST['user']))
		{
			if($model->validate() && $user->validate()){
					
				$model->attributes=$_POST['aluno'];
				$user->attributes=$_POST['user'];
				$matricula->attributes=$_POST['matricula'];
				$user->tipo='aluno';
				$model->matricula='2014'.$matricula->curso.$user->id;
				$user->save();
				
				if($model->save())
					$matricula->matricula=$model->matricula;
					$matricula->aluno=$model->id;
					$matricula->save();
					$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('update',array(
				'model'=>$model,
				'user'=>$user,
				'matricula'=>$matricula,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			//$this->loadModel()->delete();
			
			$model=$this->loadModel();
			$user=user::model()->findByPk($model->id);
			$matricula=matricula::model()->findByPk($model->matricula0->id);
				
			$matricula->delete();
			$model->delete();
			$user->delete();
			

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('aluno');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new aluno('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['aluno']))
			$model->attributes=$_GET['aluno'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=aluno::model()->findbyPk($_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='aluno-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
