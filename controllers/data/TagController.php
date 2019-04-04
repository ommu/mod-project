<?php
/**
 * TagController
 * @var $this app\components\View
 * @var $model ommu\project\models\ProjectTag
 *
 * TagController implements the CRUD actions for ProjectTag model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Create
 *	View
 *	Delete
 *	Suggest
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 8 February 2019, 15:33 WIB
 * @modified date 12 February 2019, 17:07 WIB
 * @link https://bitbucket.org/ommu/project
 *
 */

namespace ommu\project\controllers\data;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use ommu\project\models\ProjectTag;
use ommu\project\models\search\ProjectTag as ProjectTagSearch;
use ommu\project\models\Projects;
use app\models\CoreTags;

class TagController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
		return $this->redirect(['manage']);
	}

	/**
	 * Lists all ProjectTag models.
	 * @return mixed
	 */
	public function actionManage()
	{
		$project = Yii::$app->request->get('project');

		$searchModel = new ProjectTagSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$gridColumn = Yii::$app->request->get('GridColumn', null);
		$cols = [];
		if($gridColumn != null && count($gridColumn) > 0) {
			foreach($gridColumn as $key => $val) {
				if($gridColumn[$key] == 1)
					$cols[] = $key;
			}
		}
		$columns = $searchModel->getGridColumn($cols);

		if($project != null)
			$projects = Projects::findOne($project);

		$this->view->title = Yii::t('app', 'Tags');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'project' => $project,
			'projects' => $projects,
		]);
	}

	/**
	 * Creates a new ProjectTag model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new ProjectTag();
		$project = Yii::$app->request->get('project');
		if(!$project)
			throw new \yii\web\NotAcceptableHttpException(Yii::t('app', 'The requested page does not exist.'));

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			$model->project_id = $project;

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Project tag success created.'));
				return $this->redirect(['manage', 'project'=>$model->project_id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Create Tag');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single ProjectTag model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail {model-class}: {tag-id}', ['model-class' => 'Tag', 'tag-id' => $model->tag->body]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing ProjectTag model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		
		Yii::$app->session->setFlash('success', Yii::t('app', 'Project tag success deleted.'));
		return $this->redirect(['manage']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionSuggest() 
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

		$term = Yii::$app->request->get('query');
		$project = Yii::$app->request->get('project');

		if($term == null) return [];

		$model = CoreTags::find()->alias('t')
			->join('LEFT JOIN', sprintf('%s project', ProjectTag::tableName()), sprintf('t.tag_id = project.tag_id and project.project_id = %s', $project))
			->andWhere(['is', 'project.tag_id', null])
			->andWhere(['like', 't.body', $term])
			->published()->limit(15)->all();

		$result = [];
		foreach($model as $val) {
			$result[] = [
				'id' => $val->tag_id, 
				'label' => $val->body,
			];
		}
		return $result;
	}

	/**
	 * Finds the ProjectTag model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ProjectTag the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = ProjectTag::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
