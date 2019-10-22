<?php
/**
 * ProjectTag
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 8 February 2019, 11:58 WIB
 * @link https://bitbucket.org/ommu/project
 *
 * This is the model class for table "ommu_project_tag".
 *
 * The followings are the available columns in table "ommu_project_tag":
 * @property integer $id
 * @property integer $project_id
 * @property integer $tag_id
 * @property string $creation_date
 * @property integer $creation_id
 *
 * The followings are the available model relations:
 * @property Projects $project
 * @property CoreTags $tag
 * @property Users $creation
 *
 */

namespace ommu\project\models;

use Yii;
use yii\helpers\Inflector;
use app\models\CoreTags;
use ommu\users\models\Users;

class ProjectTag extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	public $tagBody;
	public $projectName;
	public $creationDisplayname;
	public $categoryId;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_project_tag';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['project_id', 'tagBody'], 'required'],
			[['project_id', 'tag_id', 'creation_id'], 'integer'],
			[['tagBody'], 'string'],
			[['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Projects::className(), 'targetAttribute' => ['project_id' => 'project_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'project_id' => Yii::t('app', 'Project'),
			'tag_id' => Yii::t('app', 'Tag'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'tagBody' => Yii::t('app', 'Tag'),
			'projectName' => Yii::t('app', 'Project'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'categoryId' => Yii::t('app', 'Category'),
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
	public function getTag()
	{
		return $this->hasOne(CoreTags::className(), ['tag_id' => 'tag_id']);
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
	 * @return \ommu\project\models\query\ProjectTag the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\project\models\query\ProjectTag(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		if(!$this->hasMethod('search'))
			return;

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('project')) {
			$this->templateColumns['categoryId'] = [
				'attribute' => 'categoryId',
				'value' => function($model, $key, $index, $column) {
					return isset($model->project->category) ? $model->project->category->title->message : '-';
					// return $model->categoryId;
				},
				'filter' => ProjectCategory::getCategory(),
			];
			$this->templateColumns['projectName'] = [
				'attribute' => 'projectName',
				'value' => function($model, $key, $index, $column) {
					return isset($model->project) ? $model->project->project_name : '-';
					// return $model->projectName;
				},
			];
		}
		if(!Yii::$app->request->get('tag')) {
			$this->templateColumns['tagBody'] = [
				'attribute' => 'tagBody',
				'value' => function($model, $key, $index, $column) {
					return isset($model->tag) ? $model->tag->body : '-';
					// return $model->tagBody;
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
					// return $model->creationDisplayname;
				},
			];
		}
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find();
			if(is_array($column))
				$model->select($column);
			else
				$model->select([$column]);
			$model = $model->where(['id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
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

		$this->tagBody = isset($this->tag) ? $this->tag->body : '';
		// $this->projectName = isset($this->project) ? $this->project->project_name : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// this->categoryId = isset($this->project->category) ? $this->project->category->title->message : '-';
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

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert)) {
			if($insert) {
				$tagBody = Inflector::slug($this->tagBody);
				if($this->tag_id == 0) {
					$tag = CoreTags::find()
						->select(['tag_id'])
						->andWhere(['body' => $tagBody])
						->one();
						
					if($tag != null)
						$this->tag_id = $tag->tag_id;
					else {
						$data = new CoreTags();
						$data->body = $this->tagBody;
						if($data->save())
							$this->tag_id = $data->tag_id;
					}
				}
			}
		}
		return true;
	}
}
