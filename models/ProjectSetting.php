<?php
/**
 * ProjectSetting
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 11 February 2019, 14:17 WIB
 * @link https://bitbucket.org/ommu/project
 *
 * This is the model class for table "ommu_project_setting".
 *
 * The followings are the available columns in table "ommu_project_setting":
 * @property integer $id
 * @property string $license
 * @property integer $permission
 * @property string $meta_description
 * @property string $meta_keyword
 * @property integer $headline
 * @property integer $headline_limit
 * @property string $headline_category
 * @property integer $photo_limit
 * @property integer $photo_resize
 * @property string $photo_resize_size
 * @property string $photo_view_size
 * @property string $photo_file_type
 * @property string $modified_date
 * @property integer $modified_id
 *
 * The followings are the available model relations:
 * @property Users $modified
 *
 */

namespace ommu\project\models;

use Yii;
use yii\helpers\Url;
use ommu\users\models\Users;

class ProjectSetting extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;

	public $gridForbiddenColumn = [];

	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_project_setting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['license', 'permission', 'meta_description', 'meta_keyword', 'headline', 'headline_limit', 'headline_category', 'photo_limit', 'photo_resize', 'photo_resize_size', 'photo_view_size', 'photo_file_type'], 'required'],
			[['permission', 'headline', 'headline_limit', 'photo_limit', 'photo_resize', 'modified_id'], 'integer'],
			[['meta_description', 'meta_keyword'], 'string'],
			//[['headline_category', 'photo_resize_size', 'photo_view_size', 'photo_file_type'], 'serialize'],
			[['license'], 'string', 'max' => 32],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'license' => Yii::t('app', 'License'),
			'permission' => Yii::t('app', 'Permission'),
			'meta_description' => Yii::t('app', 'Meta Description'),
			'meta_keyword' => Yii::t('app', 'Meta Keyword'),
			'headline' => Yii::t('app', 'Headline'),
			'headline_limit' => Yii::t('app', 'Headline Limit'),
			'headline_category' => Yii::t('app', 'Headline Category'),
			'photo_limit' => Yii::t('app', 'Image Limit'),
			'photo_resize' => Yii::t('app', 'Image Resize'),
			'photo_resize_size' => Yii::t('app', 'Image Resize Size'),
			'photo_resize_size[i]' => Yii::t('app', 'Image Resize Size'),
			'photo_resize_size[width]' => Yii::t('app', 'Width'),
			'photo_resize_size[height]' => Yii::t('app', 'Height'),
			'photo_view_size' => Yii::t('app', 'Image View'),
			'photo_view_size[small]' => Yii::t('app', 'Small'),
			'photo_view_size[small][width]' => Yii::t('app', 'Width'),
			'photo_view_size[small][height]' => Yii::t('app', 'Height'),
			'photo_view_size[medium]' => Yii::t('app', 'Medium'),
			'photo_view_size[medium][width]' => Yii::t('app', 'Width'),
			'photo_view_size[medium][height]' => Yii::t('app', 'Height'),
			'photo_view_size[large]' => Yii::t('app', 'Large'),
			'photo_view_size[large][width]' => Yii::t('app', 'Width'),
			'photo_view_size[large][height]' => Yii::t('app', 'Height'),
			'photo_file_type' => Yii::t('app', 'Image File Type'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
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
	 * @return \ommu\project\models\query\ProjectSetting the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\project\models\query\ProjectSetting(get_called_class());
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
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class'=>'text-center'],
		];
		$this->templateColumns['license'] = [
			'attribute' => 'license',
			'value' => function($model, $key, $index, $column) {
				return $model->license;
			},
		];
		$this->templateColumns['permission'] = [
			'attribute' => 'permission',
			'value' => function($model, $key, $index, $column) {
				return self::getPermission($model->permission);
			},
		];
		$this->templateColumns['meta_description'] = [
			'attribute' => 'meta_description',
			'value' => function($model, $key, $index, $column) {
				return $model->meta_description;
			},
		];
		$this->templateColumns['meta_keyword'] = [
			'attribute' => 'meta_keyword',
			'value' => function($model, $key, $index, $column) {
				return $model->meta_keyword;
			},
		];
		$this->templateColumns['headline_limit'] = [
			'attribute' => 'headline_limit',
			'value' => function($model, $key, $index, $column) {
				return $model->headline_limit;
			},
		];
		$this->templateColumns['headline_category'] = [
			'attribute' => 'headline_category',
			'value' => function($model, $key, $index, $column) {
				return serialize($model->headline_category);
			},
		];
		$this->templateColumns['photo_limit'] = [
			'attribute' => 'photo_limit',
			'value' => function($model, $key, $index, $column) {
				return $model->photo_limit;
			},
		];
		$this->templateColumns['photo_resize_size'] = [
			'attribute' => 'photo_resize_size',
			'value' => function($model, $key, $index, $column) {
				return self::getResize($model->photo_resize_size);
			},
		];
		$this->templateColumns['photo_view_size'] = [
			'attribute' => 'photo_view_size',
			'value' => function($model, $key, $index, $column) {
				return self::getViewSize($model->photo_view_size);
			},
		];
		$this->templateColumns['photo_file_type'] = [
			'attribute' => 'photo_file_type',
			'value' => function($model, $key, $index, $column) {
				return $model->photo_file_type;
			},
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
		$this->templateColumns['photo_resize'] = [
			'attribute' => 'photo_resize',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->photo_resize);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'text-center'],
		];
		$this->templateColumns['headline'] = [
			'attribute' => 'headline',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['headline', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->headline, 'Enable,Disable', true);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'text-center'],
			'format' => 'raw',
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
			$model = $model->where(['id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * function getPermission
	 */
	public static function getPermission($value=null)
	{
		$moduleName = "module name";
		$module = strtolower(Yii::$app->controller->module->id);
		if(($module = Yii::$app->moduleManager->getModule($module)) != null);
			$moduleName = strtolower($module->getName());

		$items = array(
			1 => Yii::t('app', 'Yes, the public can view {module} unless they are made private.', ['module'=>$moduleName]),
			0 => Yii::t('app', 'No, the public cannot view {module}.', ['module'=>$moduleName]),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getHeadline
	 */
	public static function getHeadline($value=null)
	{
		$items = array(
			1 => Yii::t('app', 'Enable'),
			0 => Yii::t('app', 'Disable'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getPhotoResize
	 */
	public static function getPhotoResize($value=null)
	{
		$items = array(
			1 => Yii::t('app', 'Yes, resize image after upload.'),
			0 => Yii::t('app', 'No, not resize image after upload.'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * getResize
	 */
	public static function getResize($photo_resize)
	{
		if(empty($photo_resize))
			return '-';

		$width = $photo_resize['width'] != 0 ? $photo_resize['width'] : '~';
		$height = $photo_resize['height'] != 0 ? $photo_resize['height'] : '~';

		return $width.'x'.$height;
	}

	/**
	 * getResize
	 */
	public static function getViewSize($photo_view_size)
	{
		$return = '';
		foreach ($photo_view_size as $key => $value) {
			$return .= ucfirst($key).": ".self::getResize($value)."<br/>";
		}

		return $return;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->headline_category = unserialize($this->headline_category);
		$this->photo_resize_size = unserialize($this->photo_resize_size);
		$this->photo_view_size = unserialize($this->photo_view_size);
		$photo_file_type = unserialize($this->photo_file_type);
		if(!empty($photo_file_type))
			$this->photo_file_type = $this->formatFileType($photo_file_type, false);
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if(!$this->isNewRecord) {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}

			if($this->photo_resize_size['width'] == '' && $this->photo_resize_size['height'] == '')
				$this->addError('photo_resize_size', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('photo_resize_size')]));
			else {
				if($this->photo_resize_size['width'] == '')
					$this->addError('photo_resize_size', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('photo_resize_size[width]')]));
				else if($this->photo_resize_size['height'] == '')
					$this->addError('photo_resize_size', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('photo_resize_size[height]')]));
			}

			if($this->photo_view_size['small']['width'] == '' && $this->photo_view_size['small']['height'] == '')
				$this->addError('photo_view_size[small]', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('photo_view_size[small]')]));
			else {
				if($this->photo_view_size['small']['width'] == '')
					$this->addError('photo_view_size[small]', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('photo_view_size[small][width]')]));
				else if($this->photo_view_size['small']['height'] == '')
					$this->addError('photo_view_size[small]', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('photo_view_size[small][height]')]));
			}

			if($this->photo_view_size['medium']['width'] == '' && $this->photo_view_size['medium']['height'] == '')
				$this->addError('photo_view_size[medium]', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('photo_view_size[medium]')]));
			else {
				if($this->photo_view_size['medium']['width'] == '')
					$this->addError('photo_view_size[medium]', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('photo_view_size[medium][width]')]));
				else if($this->photo_view_size['medium']['height'] == '')
					$this->addError('photo_view_size[medium]', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('photo_view_size[medium][height]')]));
			}

			if($this->photo_view_size['large']['width'] == '' && $this->photo_view_size['large']['height'] == '')
				$this->addError('photo_view_size[large]', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('photo_view_size[large]')]));
			else {
				if($this->photo_view_size['large']['width'] == '')
					$this->addError('photo_view_size[large]', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('photo_view_size[large][width]')]));
				else if($this->photo_view_size['large']['height'] == '')
					$this->addError('photo_view_size[large]', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('photo_view_size[large][height]')]));
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
			$this->headline_category = serialize($this->headline_category);
			$this->photo_resize_size = serialize($this->photo_resize_size);
			$this->photo_view_size = serialize($this->photo_view_size);
			$this->photo_file_type = serialize($this->formatFileType($this->photo_file_type));
		}
		return true;
	}
}
