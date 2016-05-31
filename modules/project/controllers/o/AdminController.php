<?php
/**
* AdminController
* Handle AdminController
* Copyright (c) 2012, Ommu Platform (ommu.co). All rights reserved.
* version: 0.0.1
* Reference start
*
* TOC :
*	Index
*	Manage
*	Add
*	Edit
*	RunAction
*	Delete
*	Publish
*	Headline
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

class AdminController extends Controller
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
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('manage','add','edit','runaction','delete','publish','headline'),
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
	 * Manages all models.
	 */
	public function actionManage() 
	{
		$model=new Projects('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Projects'])) {
			$model->attributes=$_GET['Projects'];
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
		
		if(isset($_GET['category'])) {
			$category = ProjectCategory::model()->findByPk($_GET['category']);
			$title = ': '.$category->name;
		} else {
			$title = '';
		}

		$this->pageTitle = 'Manage Projects'.$title;
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_manage',array(
			'model'=>$model,
			'columns' => $columns,
		));
	}	
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAdd() 
	{
		$model=new Projects;
		$setting = ProjectSetting::model()->findByPk(1,array(
			'select' => 'meta_keyword',
		));

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Projects'])) {
			$model->attributes=$_POST['Projects'];

			if($model->save()) {
				Yii::app()->user->setFlash('success', 'Project success created.');
				$this->redirect(array('edit','id'=>$model->project_id));
			}
		}

		$this->pageTitle = 'Create Project';
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_add',array(
			'model'=>$model,
			'setting'=>$setting,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionEdit($id) 
	{
		$model=$this->loadModel($id);

		$setting = ProjectSetting::model()->findByPk(1,array(
			'select' => 'meta_keyword, media_limit',
		));
		$team = ProjectTeam	::model()->findAll(array(
			'condition' => 'project_id = :id',
			'params' => array(
				':id' => $model->project_id,
			),
			'order' => 'team_id DESC',
			'limit' => 30,
		));
		$tag = ProjectTag::model()->findAll(array(
			'condition' => 'project_id = :id',
			'params' => array(
				':id' => $model->project_id,
			),
			'order' => 'id DESC',
			'limit' => 30,
		));

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Projects'])) {
			$model->attributes=$_POST['Projects'];

			if($setting->media_limit != 1) {
				$jsonError = CActiveForm::validate($model);
				if(strlen($jsonError) > 2) {
					$errors = $model->getErrors();
					$summary['msg'] = "<div class='errorSummary'><strong>Please fix the following input errors:</strong>";
					$summary['msg'] .= "<ul>";
					foreach($errors as $key => $value) {
						$summary['msg'] .= "<li>{$value[0]}</li>";
					}
					$summary['msg'] .= "</ul></div>";

					$message = json_decode($jsonError, true);
					$merge = array_merge_recursive($summary, $message);
					$encode = json_encode($merge);
					echo $encode;

				} else {
					if(isset($_GET['enablesave']) && $_GET['enablesave'] == 1) {
						if($model->save()) {
							echo CJSON::encode(array(
								'type' => 0,
								'msg' => '<div class="errorSummary success"><strong>Project success updated.</strong></div>',
							));
						} else {
							print_r($model->getErrors());
						}
					}
				}
				Yii::app()->end();

			} else {
				if($model->save()) {
					Yii::app()->user->setFlash('success', 'Project success updated.');
					$this->redirect(array('edit','id'=>$model->project_id));
				}
			}
		}

		$this->pageTitle = 'Update Project: '.$model->title;
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_edit',array(
			'model'=>$model,
			'setting'=>$setting,
			'team'=>$team,
			'tag'=>$tag,
		));
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionRunAction() {
		$id       = $_POST['trash_id'];
		$criteria = null;
		$actions  = $_GET['action'];

		if(count($id) > 0) {
			$criteria = new CDbCriteria;
			$criteria->addInCondition('id', $id);

			if($actions == 'publish') {
				Projects::model()->updateAll(array(
					'published' => 1,
				),$criteria);
			} elseif($actions == 'unpublish') {
				Projects::model()->updateAll(array(
					'published' => 0,
				),$criteria);
			} elseif($actions == 'trash') {
				Projects::model()->updateAll(array(
					'published' => 2,
				),$criteria);
			} elseif($actions == 'delete') {
				Projects::model()->deleteAll($criteria);
			}
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('manage'));
		}
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
					echo CJSON::encode(array(
						'type' => 5,
						'get' => Yii::app()->controller->createUrl('manage'),
						'id' => 'partial-projects',
						'msg' => '<div class="errorSummary success"><strong>Project success deleted.</strong></div>',
					));
				}
			}

		} else {
			$this->dialogDetail = true;
			$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
			$this->dialogWidth = 350;

			$this->pageTitle = 'Delete Project: '.$model->title;
			$this->pageDescription = '';
			$this->pageMeta = '';
			$this->render('admin_delete');
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionPublish($id) 
	{
		$model=$this->loadModel($id);
		if($model->publish == 1) {
			$title = 'Unpublish';
			$replace = 0;
		} else {
			$title = 'Publish';
			$replace = 1;
		}

		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			if(isset($id)) {
				//change value active or publish
				$model->publish = $replace;

				if($model->update()) {
					echo CJSON::encode(array(
						'type' => 5,
						'get' => Yii::app()->controller->createUrl('manage'),
						'id' => 'partial-projects',
						'msg' => '<div class="errorSummary success"><strong>Project success updated.</strong></div>',
					));
				}
			}

		} else {
			$this->dialogDetail = true;
			$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
			$this->dialogWidth = 350;

			$this->pageTitle = $title.': '.$model->title;
			$this->pageDescription = '';
			$this->pageMeta = '';
			$this->render('admin_publish',array(
				'title'=>$title,
				'model'=>$model,
			));
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionHeadline($id) 
	{
		$model=$this->loadModel($id);

		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			if(isset($id)) {
				//change value active or publish
				$model->headline = 1;
				$model->publish = 1;

				if($model->update()) {
					echo CJSON::encode(array(
						'type' => 5,
						'get' => Yii::app()->controller->createUrl('manage'),
						'id' => 'partial-projects',
						'msg' => '<div class="errorSummary success"><strong>Project success updated.</strong></div>',
					));
				}
			}

		} else {
			$this->dialogDetail = true;
			$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
			$this->dialogWidth = 350;

			$this->pageTitle = 'Headline: '.$model->title;
			$this->pageDescription = '';
			$this->pageMeta = '';
			$this->render('admin_headline');
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) 
	{
		$model = Projects::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='projects-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
