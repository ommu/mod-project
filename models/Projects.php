<?php
/**
 * Projects
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 7 February 2019, 17:57 WIB
 * @modified date 8 February 2019, 07:12 WIB
 * @link https://bitbucket.org/ommu/project
 *
 * This is the model class for table "ommu_projects".
 *
 * The followings are the available columns in table "ommu_projects":
 * @property integer $project_id
 * @property integer $publish
 * @property integer $cat_id
 * @property integer $company_id
 * @property string $project_name
 * @property string $project_desc
 * @property string $status
 * @property string $start_date
 * @property string $finish_date
 * @property integer $headline
 * @property integer $comment
 * @property string $headline_date
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property ProjectPhoto[] $photos
 * @property ProjectTag[] $tags
 * @property ProjectTeam[] $teams
 * @property ProjectCategory $category
 * @property IpediaCompanies $company
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\project\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\behaviors\SluggableBehavior;
use ommu\users\models\Users;
use ommu\ipedia\models\IpediaCompanies;

class Projects extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['project_desc', 'start_date', 'finish_date', 'comment', 'headline_date', 'creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date', 'slug'];

	public $categoryName;
	public $companyName;
	public $creationDisplayname;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_projects';
	}

	/**
	 * behaviors model class.
	 */
	public function behaviors() {
		return [
			[
				'class' => SluggableBehavior::className(),
				'attribute' => 'project_name',
				'immutable' => true,
				'ensureUnique' => true,
			],
		];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['publish', 'cat_id', 'project_name', 'status'], 'required'],
			[['publish', 'cat_id', 'company_id', 'headline', 'comment', 'creation_id', 'modified_id'], 'integer'],
			[['project_desc', 'status'], 'string'],
			[['company_id', 'start_date', 'finish_date'], 'safe'],
			[['project_name', 'slug'], 'string', 'max' => 64],
			[['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectCategory::className(), 'targetAttribute' => ['cat_id' => 'cat_id']],
			[['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => IpediaCompanies::className(), 'targetAttribute' => ['company_id' => 'company_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'project_id' => Yii::t('app', 'Project'),
			'publish' => Yii::t('app', 'Publish'),
			'cat_id' => Yii::t('app', 'Category'),
			'company_id' => Yii::t('app', 'Company'),
			'project_name' => Yii::t('app', 'Project Name'),
			'project_desc' => Yii::t('app', 'Project Desc'),
			'status' => Yii::t('app', 'Status'),
			'start_date' => Yii::t('app', 'Start Date'),
			'finish_date' => Yii::t('app', 'Finish Date'),
			'headline' => Yii::t('app', 'Headline'),
			'comment' => Yii::t('app', 'Comment'),
			'headline_date' => Yii::t('app', 'Headline Date'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'slug' => Yii::t('app', 'Slug'),
			'photos' => Yii::t('app', 'Photos'),
			'tags' => Yii::t('app', 'Tags'),
			'teams' => Yii::t('app', 'Teams'),
			'categoryName' => Yii::t('app', 'Category'),
			'companyName' => Yii::t('app', 'Company'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPhotos($count=false, $publish=1)
	{
		if($count == false) {
			return $this->hasMany(ProjectPhoto::className(), ['project_id' => 'project_id'])
				->andOnCondition([sprintf('%s.publish', ProjectPhoto::tableName()) => $publish]);
		}

		$model = ProjectPhoto::find()
			->where(['project_id' => $this->project_id]);
		if($publish == 0)
			$model->unpublish();
		elseif($publish == 1)
			$model->published();
		elseif($publish == 2)
			$model->deleted();
		$photos = $model->count();

		return $photos ? $photos : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTags($count=false)
	{
		if($count == false)
			return $this->hasMany(ProjectTag::className(), ['project_id' => 'project_id']);

		$model = ProjectTag::find()
			->where(['project_id' => $this->project_id]);
		$tags = $model->count();

		return $tags ? $tags : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTeams($count=false, $publish=1)
	{
		if($count == false) {
			return $this->hasMany(ProjectTeam::className(), ['project_id' => 'project_id'])
				->andOnCondition([sprintf('%s.publish', ProjectTeam::tableName()) => $publish]);
		}

		$model = ProjectTeam::find()
			->where(['project_id' => $this->project_id]);
		if($publish == 0)
			$model->unpublish();
		elseif($publish == 1)
			$model->published();
		elseif($publish == 2)
			$model->deleted();
		$teams = $model->count();

		return $teams ? $teams : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategory()
	{
		return $this->hasOne(ProjectCategory::className(), ['cat_id' => 'cat_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCompany()
	{
		return $this->hasOne(IpediaCompanies::className(), ['company_id' => 'company_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\project\models\query\Projects the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\project\models\query\Projects(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('category')) {
			$this->templateColumns['cat_id'] = [
				'attribute' => 'cat_id',
				'value' => function($model, $key, $index, $column) {
					return isset($model->category) ? $model->category->title->message : '-';
					// return $model->categoryName;
				},
				'filter' => ProjectCategory::getCategory(),
			];
		}
		if(!Yii::$app->request->get('company')) {
			$this->templateColumns['companyName'] = [
				'attribute' => 'companyName',
				'value' => function($model, $key, $index, $column) {
					return isset($model->company) ? $model->company->company_name : '-';
					// return $model->companyName;
				},
			];
		}
		$this->templateColumns['project_name'] = [
			'attribute' => 'project_name',
			'value' => function($model, $key, $index, $column) {
				return $model->project_name;
			},
		];
		$this->templateColumns['project_desc'] = [
			'attribute' => 'project_desc',
			'value' => function($model, $key, $index, $column) {
				return $model->project_desc;
			},
			'format' => 'html',
		];
		$this->templateColumns['start_date'] = [
			'attribute' => 'start_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDate($model->start_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'start_date'),
		];
		$this->templateColumns['finish_date'] = [
			'attribute' => 'finish_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDate($model->finish_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'finish_date'),
		];
		$this->templateColumns['headline_date'] = [
			'attribute' => 'headline_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->headline_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'headline_date'),
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		if(!Yii::$app->request->get('creation')) {
			$this->templateColumns['creationDisplayname'] = [
				'attribute' => 'creationDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->creation) ? $model->creation->displayname : '-';
					// return $model->creationDisplayname;
				},
			];
		}
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modifiedDisplayname'] = [
				'attribute' => 'modifiedDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
					// return $model->modifiedDisplayname;
				},
			];
		}
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['slug'] = [
			'attribute' => 'slug',
			'value' => function($model, $key, $index, $column) {
				return $model->slug;
			},
		];
		$this->templateColumns['photos'] = [
			'attribute' => 'photos',
			'value' => function($model, $key, $index, $column) {
				$photos = $model->getPhotos(true);
				return Html::a($photos, ['data/photo/manage', 'project'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} photos', ['count'=>$photos])]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['tags'] = [
			'attribute' => 'tags',
			'value' => function($model, $key, $index, $column) {
				$tags = $model->getTags(true);
				return Html::a($tags, ['data/tag/manage', 'project'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} tags', ['count'=>$tags])]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['teams'] = [
			'attribute' => 'teams',
			'value' => function($model, $key, $index, $column) {
				$teams = $model->getTeams(true);
				return Html::a($teams, ['data/team/manage', 'project'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} teams', ['count'=>$teams])]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['status'] = [
			'attribute' => 'status',
			'value' => function($model, $key, $index, $column) {
				return self::getStatus($model->status);
			},
			'filter' => self::getStatus(),
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['headline'] = [
			'attribute' => 'headline',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['headline', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->headline, 'Headline,Unheadline', true);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['comment'] = [
			'attribute' => 'comment',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['comment', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->comment, 'Enable,Disable');
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['publish', 'id'=>$model->primaryKey]);
					return $this->quickAction($url, $model->publish);
				},
				'filter' => $this->filterYesNo(),
				'contentOptions' => ['class'=>'center'],
				'format' => 'raw',
			];
		}
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find()
				->select([$column])
				->where(['project_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * function getStatus
	 */
	public static function getStatus($value=null)
	{
		$items = array(
			'wait' => Yii::t('app', 'Wait'),
			'process' => Yii::t('app', 'Process'),
			'done' => Yii::t('app', 'Done'),
			'pending' => Yii::t('app', 'Pending'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->categoryName = isset($this->category) ? $this->category->title->message : '-';
		// $this->companyName = isset($this->company) ? $this->company->company_name : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				if($this->creation_id == null)
					$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			} else {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert)) {
			$this->start_date = Yii::$app->formatter->asDate($this->start_date, 'php:Y-m-d');
			$this->finish_date = Yii::$app->formatter->asDate($this->finish_date, 'php:Y-m-d');
		}
		return true;
	}
}
