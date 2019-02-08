<?php
/**
 * ProjectTeam
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 8 February 2019, 12:00 WIB
 * @link https://bitbucket.org/ommu/project
 *
 * This is the model class for table "ommu_project_team".
 *
 * The followings are the available columns in table "ommu_project_team":
 * @property integer $team_id
 * @property integer $publish
 * @property integer $project_id
 * @property integer $user_id
 * @property integer $position_id
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property Projects $project
 * @property Users $user
 * @property IpediaPositions $position
 * @property Users $creation
 *
 */

namespace ommu\project\models;

use Yii;
use yii\helpers\Url;
use ommu\users\models\Users;
use ommu\ipedia\models\IpediaPositions;

class ProjectTeam extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = [];

	public $projectName;
	public $userDisplayname;
	public $positionName;
	public $creationDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_project_team';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['project_id', 'user_id', 'position_id'], 'required'],
			[['publish', 'project_id', 'user_id', 'position_id', 'creation_id'], 'integer'],
			[['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Projects::className(), 'targetAttribute' => ['project_id' => 'project_id']],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
			[['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => IpediaPositions::className(), 'targetAttribute' => ['position_id' => 'position_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'team_id' => Yii::t('app', 'Team'),
			'publish' => Yii::t('app', 'Publish'),
			'project_id' => Yii::t('app', 'Project'),
			'user_id' => Yii::t('app', 'User'),
			'position_id' => Yii::t('app', 'Position'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'projectName' => Yii::t('app', 'Project'),
			'userDisplayname' => Yii::t('app', 'User'),
			'positionName' => Yii::t('app', 'Position'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getProject()
	{
		return $this->hasOne(Projects::className(), ['project_id' => 'project_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPosition()
	{
		return $this->hasOne(IpediaPositions::className(), ['position_id' => 'position_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\project\models\query\ProjectTeam the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\project\models\query\ProjectTeam(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class'  => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('project')) {
			$this->templateColumns['projectName'] = [
				'attribute' => 'projectName',
				'value' => function($model, $key, $index, $column) {
					return isset($model->project) ? $model->project->project_name : '-';
				},
			];
		}
		if(!Yii::$app->request->get('user')) {
			$this->templateColumns['userDisplayname'] = [
				'attribute' => 'userDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? $model->user->displayname : '-';
				},
			];
		}
		if(!Yii::$app->request->get('position')) {
			$this->templateColumns['positionName'] = [
				'attribute' => 'positionName',
				'value' => function($model, $key, $index, $column) {
					return isset($model->position) ? $model->position->position_name : '-';
				},
			];
		}
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
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'filter' => $this->filterYesNo(),
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['publish', 'id'=>$model->primaryKey]);
					return $this->quickAction($url, $model->publish, 'Active,Deactive');
				},
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
				->where(['team_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->projectName = isset($this->project) ? $this->project->project_name : '-';
		// $this->userDisplayname = isset($this->user) ? $this->user->displayname : '-';
		// $this->positionName = isset($this->position) ? $this->position->position_name : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
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
			}
		}
		return true;
	}
}
