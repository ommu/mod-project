<?php
/**
 * TeamController
 * Handle TeamController
 * Copyright (c) 2012, Ommu Platform (ommu.co). All rights reserved.
 * version: 0.0.1
 * Reference start
 *
 * TOC :
 *	Index
 *	Suggest
 *	AjaxAdd
 *	Manage
 *	Delete
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2012 Ommu Platform (ommu.co)
 * @link http://company.ommu.co
 * @contact (+62)856-299-4114
 *
 *----------------------------------------------------------------------------------------------------------
 */

class TeamController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';
	public $defaultAction = 'index';

	/**
	 * Initialize admin page theme
	 */
	public function init() 
	{
		if(!Yii::app()->user->isGuest) {
			if(Yii::app()->user->level == 1) {
				$arrThemes = Utility::getCurrentTemplate('admin');
				Yii::app()->theme = $arrThemes['folder'];
				$this->layout = $arrThemes['layout'];
			} else {
				$this->redirect(Yii::app()->createUrl('site/login'));
			}
		} else {
			$this->redirect(Yii::app()->createUrl('site/login'));
		}
	}

	/**
	 * @return array action filters
	 */
	public function filters() 
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level)',
				//'expression'=>'isset(Yii::app()->user->level) && (Yii::app()->user->level != 1)',
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('suggest',
					'manage','delete'),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level) && (Yii::app()->user->level == 1)',
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array(),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Lists all models.
	 */
	public function actionIndex() 
	{
		$this->redirect(array('manage'));
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionSuggest($limit=10) {
		if(isset($_GET['term'])) {
			$criteria = new CDbCriteria;	
			$criteria->condition = 'displayname LIKE :displayname';
			$criteria->select	= 'user_id, displayname';
			$criteria->limit = $limit;
			$criteria->order = 'user_id ASC';
			$criteria->params = array(
				':displayname' => '%' . strtolower($_GET['term']) . '%',
			);
			$model = Users::model()->findAll($criteria);

			if($model) {
				foreach($model as $items) {
					$result[] = array('id' => $items->user_id, 'value' => $items->displayname);
				}
			}
		}
		echo CJSON::encode($result);
		Yii::app()->end();
	}
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAjaxAdd() 
	{
		$model=new ProjectTeam;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['project_id'], $_POST['user_id'])) {
			$model->project_id = $_POST['project_id'];
			$model->user_id = $_POST['user_id'];

			if($model->save()) {
				if(isset($_GET['type']) && $_GET['type'] == 'project')
					$url = Yii::app()->controller->createUrl('delete',array('id'=>$model->team_id,'type'=>'project'));
				else 
					$url = Yii::app()->controller->createUrl('delete',array('id'=>$model->team_id));
				echo CJSON::encode(array(
					'data' => '<div>'.$model->user->displayname.'<a href="'.$url.'" title="'.'Delete'.'">'.'Delete'.'</a></div>',
				));
			}
		}
	}

	/**
	 * Manages all models.
	 */
	public function actionManage() 
	{
		$model=new ProjectTeam('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ProjectTeam'])) {
			$model->attributes=$_GET['ProjectTeam'];
		}

		$columnTemp = array();
		if(isset($_GET['GridColumn'])) {
			foreach($_GET['GridColumn'] as $key => $val) {
				if($_GET['GridColumn'][$key] == 1) {
					$columnTemp[] = $key;
				}
			}
		}
		$columns = $model->getGridColumn($columnTemp);

		$this->pageTitle = 'Project Teams Manage';
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_manage',array(
			'model'=>$model,
			'columns' => $columns,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id) 
	{
		$model=$this->loadModel($id);
		
		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			if(isset($id)) {
				if($model->delete()) {
					if(isset($_GET['type']) && $_GET['type'] == 'project') {
						echo CJSON::encode(array(
							'type' => 4,
						));
					} else {
						echo CJSON::encode(array(
							'type' => 5,
							'get' => Yii::app()->controller->createUrl('manage'),
							'id' => 'partial-project-team',
							'msg' => '<div class="errorSummary success"><strong>ProjectTeam success deleted.</strong></div>',
						));
					}
				}
			}

		} else {
			if(isset($_GET['type']) && $_GET['type'] == 'project')
				$dialogGroundUrl = Yii::app()->controller->createUrl('admin/edit', array('id'=>$model->project_id));
			else
				$dialogGroundUrl = Yii::app()->controller->createUrl('manage');
			$this->dialogDetail = true;
			$this->dialogGroundUrl = $dialogGroundUrl;
			$this->dialogWidth = 350;

			$this->pageTitle = 'ProjectTeam Delete: '.$model->user->displayname.' [at] '.$model->project->title;
			$this->pageDescription = '';
			$this->pageMeta = '';
			$this->render('admin_delete');
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) 
	{
		$model = ProjectTeam::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model) 
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='project-team-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
