<?php
/**
 * AdminController
 * @var $this ommu\project\controllers\AdminController
 * @var $model ommu\project\models\Projects
 *
 * AdminController implements the CRUD actions for Projects model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Create
 *	Update
 *	View
 *	Delete
 *	RunAction
 *	Publish
 *	Headline
 *	Comment
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 7 February 2019, 19:54 WIB
 * @modified date 8 February 2019, 15:21 WIB
 * @link https://bitbucket.org/ommu/project
 *
 */

namespace ommu\project\controllers;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\project\models\Projects;
use ommu\project\models\search\Projects as ProjectsSearch;

class AdminController extends Controller
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
                    'publish' => ['POST'],
					'headline' => ['POST'],
					'comment' => ['POST'],
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
	 * Lists all Projects models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new ProjectsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $gridColumn = Yii::$app->request->get('GridColumn', null);
        $cols = [];
        if ($gridColumn != null && count($gridColumn) > 0) {
            foreach ($gridColumn as $key => $val) {
                if ($gridColumn[$key] == 1) {
                    $cols[] = $key;
                }
            }
        }
        $columns = $searchModel->getGridColumn($cols);

        if (($company = Yii::$app->request->get('company')) != null) {
            $company = \ommu\ipedia\models\IpediaCompanies::findOne($company);
        }
        if (($category = Yii::$app->request->get('category')) != null) {
            $category = \ommu\project\models\ProjectCategory::findOne($category);
        }

		$this->view->title = Yii::t('app', 'Projects');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'company' => $company,
			'category' => $category,
		]);
	}

	/**
	 * Creates a new Projects model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
        $model = new Projects();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Project success created.'));
                return $this->redirect(['manage']);
                //return $this->redirect(['view', 'id' => $model->project_id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Create Project');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing Projects model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Project success updated.'));
                return $this->redirect(['manage']);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Update Project: {project-name}', ['project-name' => $model->project_name]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single Projects model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail Project: {project-name}', ['project-name' => $model->project_name]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
			'small' => false,
		]);
	}

	/**
	 * Deletes an existing Projects model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

        if ($model->save(false, ['publish', 'modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Project success deleted.'));
			return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
		}
	}

	/**
	 * actionPublish an existing Projects model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

        if ($model->save(false, ['publish', 'modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Project success updated.'));
			return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
		}
	}

	/**
	 * actionHeadline an existing Projects model.
	 * If headline is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionHeadline($id)
	{
		$model = $this->findModel($id);
		$model->headline = 1;
		$model->publish  = 1;

        if ($model->save(false, ['publish', 'headline', 'modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Project success updated.'));
			return $this->redirect(['manage']);
		}
	}

	/**
	 * actionComment an existing Projects model.
	 * If comment is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionComment($id)
	{
		$model = $this->findModel($id);
		$replace = $model->comment == 1 ? 0 : 1;
		$model->comment = $replace;
		
        if ($model->save(false, ['comment', 'modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Project success updated.'));
			return $this->redirect(['manage']);
		}
	}

	/**
	 * Finds the Projects model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Projects the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = Projects::findOne($id)) !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
