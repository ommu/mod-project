<?php
/**
 * ProjectPhoto
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 8 February 2019, 11:58 WIB
 * @link https://bitbucket.org/ommu/project
 *
 * This is the model class for table "ommu_project_photo".
 *
 * The followings are the available columns in table "ommu_project_photo":
 * @property integer $photo_id
 * @property integer $publish
 * @property integer $cover
 * @property integer $project_id
 * @property string $photo
 * @property string $photo_title
 * @property string $photo_caption
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property Projects $project
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\project\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\UploadedFile;
use thamtech\uuid\helpers\UuidHelper;
use ommu\users\models\Users;

class ProjectPhoto extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;

	public $gridForbiddenColumn = ['photo', 'photo_caption', 'modified_date', 'modifiedDisplayname', 'updated_date'];

	public $old_photo;
	public $projectName;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $categoryId;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_project_photo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['project_id'], 'required'],
			[['publish', 'cover', 'project_id', 'creation_id', 'modified_id'], 'integer'],
			[['photo', 'photo_caption'], 'string'],
			[['photo', 'photo_title', 'photo_caption'], 'safe'],
			[['photo_title'], 'string', 'max' => 64],
			[['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Projects::className(), 'targetAttribute' => ['project_id' => 'project_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'photo_id' => Yii::t('app', 'Photo'),
			'publish' => Yii::t('app', 'Publish'),
			'cover' => Yii::t('app', 'Cover'),
			'project_id' => Yii::t('app', 'Project'),
			'photo' => Yii::t('app', 'Photo'),
			'photo_title' => Yii::t('app', 'Photo Title'),
			'photo_caption' => Yii::t('app', 'Photo Caption'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'old_photo' => Yii::t('app', 'Old Photo'),
			'projectName' => Yii::t('app', 'Project'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
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
	 * @return \ommu\project\models\query\ProjectPhoto the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\project\models\query\ProjectPhoto(get_called_class());
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
			'contentOptions' => ['class'=>'text-center'],
		];
		$this->templateColumns['categoryId'] = [
			'attribute' => 'categoryId',
			'value' => function($model, $key, $index, $column) {
				return isset($model->project->category) ? $model->project->category->title->message : '-';
				// return $model->categoryId;
			},
			'filter' => ProjectCategory::getCategory(),
			'visible' => !Yii::$app->request->get('project') ? true : false,
		];
		$this->templateColumns['projectName'] = [
			'attribute' => 'projectName',
			'value' => function($model, $key, $index, $column) {
				return isset($model->project) ? $model->project->project_name : '-';
				// return $model->projectName;
			},
			'visible' => !Yii::$app->request->get('project') ? true : false,
		];
		$this->templateColumns['photo'] = [
			'attribute' => 'photo',
			'value' => function($model, $key, $index, $column) {
				$uploadPath = join('/', [self::getUploadPath(false), $model->project_id]);
				return $model->photo ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->photo])), ['alt'=>$model->photo]) : '-';
			},
			'format' => 'html',
		];
		$this->templateColumns['photo_title'] = [
			'attribute' => 'photo_title',
			'value' => function($model, $key, $index, $column) {
				return $model->photo_title;
			},
		];
		$this->templateColumns['photo_caption'] = [
			'attribute' => 'photo_caption',
			'value' => function($model, $key, $index, $column) {
				return $model->photo_caption;
			},
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		$this->templateColumns['creationDisplayname'] = [
			'attribute' => 'creationDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->creation) ? $model->creation->displayname : '-';
				// return $model->creationDisplayname;
			},
			'visible' => !Yii::$app->request->get('creation') ? true : false,
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		$this->templateColumns['modifiedDisplayname'] = [
			'attribute' => 'modifiedDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->modified) ? $model->modified->displayname : '-';
				// return $model->modifiedDisplayname;
			},
			'visible' => !Yii::$app->request->get('modified') ? true : false,
		];
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['cover'] = [
			'attribute' => 'cover',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->cover);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'text-center'],
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['publish', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->publish);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'text-center'],
			'format' => 'raw',
			'visible' => !Yii::$app->request->get('trash') ? true : false,
		];
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
			$model = $model->where(['photo_id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * @param returnAlias set true jika ingin kembaliannya path alias atau false jika ingin string
	 * relative path. default true.
	 */
	public static function getUploadPath($returnAlias=true) 
	{
		return ($returnAlias ? Yii::getAlias('@public/project') : 'project');
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->old_photo = $this->photo;
		// $this->projectName = isset($this->project) ? $this->project->project_name : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
		// this->categoryId = isset($this->project->category) ? $this->project->category->title->message : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			$photoFileType = ['bmp','gif','jpg','png'];
			$photo = UploadedFile::getInstance($this, 'photo');

			if($photo instanceof UploadedFile && !$photo->getHasError()) {
				if(!in_array(strtolower($photo->getExtension()), $photoFileType)) {
					$this->addError('photo', Yii::t('app', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}', [
						'name'=>$photo->name,
						'extensions'=>$this->formatFileType($photoFileType, false),
					]));
				}
			} else {
				if($this->isNewRecord || (!$this->isNewRecord && $this->old_photo == ''))
					$this->addError('photo', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('photo')]));
			}

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
			$uploadPath = join('/', [self::getUploadPath(), $this->project_id]);
			$verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);
			$this->createUploadDirectory(self::getUploadPath(), $this->project_id);

			$this->photo = UploadedFile::getInstance($this, 'photo');
			if($this->photo instanceof UploadedFile && !$this->photo->getHasError()) {
				$fileName = join('-', [time(), UuidHelper::uuid()]).'.'.strtolower($this->photo->getExtension()); 
				if($this->photo->saveAs(join('/', [$uploadPath, $fileName]))) {
					if(!$insert && $this->old_photo != '' && file_exists(join('/', [$uploadPath, $this->old_photo])))
						rename(join('/', [$uploadPath, $this->old_photo]), join('/', [$verwijderenPath, $this->project_id.'-'.time().'_change_'.$this->old_photo]));
					$this->photo = $fileName;
				}
			} else {
				if($this->photo == '')
					$this->photo = $this->old_photo;
			}
		}
		return true;
	}

	/**
	 * After delete attributes
	 */
	public function afterDelete()
	{
		parent::afterDelete();

		$uploadPath = join('/', [self::getUploadPath(), $this->project_id]);
		$verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);

		if($this->photo != '' && file_exists(join('/', [$uploadPath, $this->photo])))
			rename(join('/', [$uploadPath, $this->photo]), join('/', [$verwijderenPath, $this->project_id.'-'.time().'_deleted_'.$this->photo]));
	}
}
