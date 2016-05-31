<?php

/**
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link http://company.ommu.co
 * @contact (+62)856-299-4114
 *
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 *
 * --------------------------------------------------------------------------------------
 *
 * This is the model class for table "ommu_project_media".
 *
 * The followings are the available columns in table 'ommu_project_media':
 * @property string $media_id
 * @property string $project_id
 * @property integer $orders
 * @property integer $cover
 * @property string $media
 * @property string $creation_date
 *
 * The followings are the available model relations:
 * @property OmmuProjects $project
 */
class ProjectMedia extends CActiveRecord
{
	public $defaultColumns = array();
	public $old_media;
	
	// Variable Search
	public $project_search;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProjectMedia the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ommu_project_media';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('project_id', 'required'),
			array('orders, cover', 'numerical', 'integerOnly'=>true),
			array('project_id', 'length', 'max'=>11),
			array('media,
				old_media', 'length', 'max'=>64),
			array('media', 'file', 'types' => 'jpg, jpeg, png, gif', 'allowEmpty' => true),
			array('media, creation_date,
				old_media', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('media_id, project_id, orders, cover, media, creation_date,
				project_search', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'project' => array(self::BELONGS_TO, 'Projects', 'project_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'media_id' => 'Media',
			'project_id' => 'Project',
			'orders' => 'Order',
			'cover' => 'Cover',
			'media' => 'Media',
			'creation_date' => 'Creation Date',
			'old_media' => 'Old Media',
			'project_search' => 'Project',
		);
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('t.media_id',$this->media_id);
		if(isset($_GET['project'])) {
			$criteria->compare('t.project_id',$_GET['project']);
		} else {
			$criteria->compare('t.project_id',$this->project_id);
		}
		$criteria->compare('t.orders',$this->orders);
		$criteria->compare('t.cover',$this->cover);
		$criteria->compare('t.media',strtolower($this->media),true);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.creation_date)',date('Y-m-d', strtotime($this->creation_date)));
		
		// Custom Search
		$criteria->with = array(
			'project' => array(
				'alias'=>'project',
				'select'=>'title'
			),
		);
		$criteria->compare('project.title',strtolower($this->project_search), true);

		if(!isset($_GET['ProjectMedia_sort']))
			//$criteria->order = 'media_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>30,
			),
		));
	}


	/**
	 * Get column for CGrid View
	 */
	public function getGridColumn($columns=null) {
		if($columns !== null) {
			foreach($columns as $val) {
				/*
				if(trim($val) == 'enabled') {
					$this->defaultColumns[] = array(
						'name'  => 'enabled',
						'value' => '$data->enabled == 1? "Ya": "Tidak"',
					);
				}
				*/
				$this->defaultColumns[] = $val;
			}
		} else {
			//$this->defaultColumns[] = 'media_id';
			$this->defaultColumns[] = 'project_id';
			$this->defaultColumns[] = 'orders';
			$this->defaultColumns[] = 'cover';
			$this->defaultColumns[] = 'media';
			$this->defaultColumns[] = 'creation_date';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->defaultColumns) == 0) {
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			if(!isset($_GET['project'])) {
				$this->defaultColumns[] = array(
					'name' => 'project_search',
					'value' => '$data->project->title."<br/><span>".Utility::shortText(Utility::hardDecode($data->project->body),150)."</span>"',
					'htmlOptions' => array(
						'class' => 'bold',
					),
					'type' => 'raw',
				);
			}
			$this->defaultColumns[] = array(
				'name' => 'media',
				'value' => 'CHtml::link($data->media, Yii::app()->request->baseUrl.\'/public/project/\'.$data->project_id.\'/\'.$data->media, array(\'target\' => \'_blank\'))',
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'creation_date',
				'value' => 'Utility::dateFormat($data->creation_date)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'=>$this, 
					'attribute'=>'creation_date', 
					'language' => 'ja',
					'i18nScriptFile' => 'jquery.ui.datepicker-en.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'creation_date_filter',
					),
					'options'=>array(
						'showOn' => 'focus',
						'dateFormat' => 'dd-mm-yy',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
					),
				), true),
			);
			$this->defaultColumns[] = array(
				'name' => 'cover',
				'value' => '$data->cover == 1 ? Chtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : Chtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter'=>array(
					1=>'Yes',
					0=>'No',
				),
				'type' => 'raw',
			);
		}
		parent::afterConstruct();
	}

	/**
	 * get photo project
	 */
	public static function getPhoto($id, $type=null) {
		if($type == null) {
			$model = self::model()->findAll(array(
				//'select' => 'project_id, orders, media',
				'condition' => 'project_id = :id',
				'params' => array(
					':id' => $id,
				),
				//'order'=> 'orders ASC',
			));
		} else {
			$model = self::model()->findAll(array(
				//'select' => 'project_id, orders, media',
				'condition' => 'project_id = :id AND cover = :cover',
				'params' => array(
					':id' => $id,
					':cover' => $type,
				),
				//'order'=> 'orders ASC',
			));
		}

		return $model;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() {
		if(parent::beforeSave()) {
		
			//Update project photo
			$controller = strtolower(Yii::app()->controller->id);
			if(!$this->isNewRecord && $controller == 'media' && !Yii::app()->request->isAjaxRequest) {
				$project_path = "public/project/".$this->project_id;
				$this->media = CUploadedFile::getInstance($this, 'media');
				if($this->media instanceOf CUploadedFile) {
					$fileName = time().'_'.$this->project_id.'.'.strtolower($this->media->extensionName);
					if($this->media->saveAs($project_path.'/'.$fileName)) {
						rename($project_path.'/'.$this->old_media, 'public/project/verwijderen/'.$this->project_id.'_'.$this->old_media);
						$this->media = $fileName;
					}
				}
				if($this->media == '') {
					$this->media = $this->old_media;
				}
			}
		}
		return true;
	}
	
	/**
	 * After save attributes
	 */
	protected function afterSave() {
		parent::afterSave();

		//set flex cover in project
		//if($this->cover == 1 || count(self::getPhoto($this->project_id)) == 1) {
		if($this->cover == 1) {
			$cover = Projects::model()->findByPk($this->project_id);
			$cover->media_id = $this->media_id;
			$cover->update();
		}

		$setting = ProjectSetting::getInfo('media_limit, media_resize, media_resize_size, media_large_width, media_large_height', 'many');
		
		//create thumb image
		if($setting->media_resize == 1) {
			Yii::import('ext.phpthumb.PhpThumbFactory');
			$project_path = "public/project/".$this->project_id;
			$projectImg = PhpThumbFactory::create($project_path.'/'.$this->media, array('jpegQuality' => 90, 'correctPermissions' => true));
			$resizeSize = explode(',', $setting->media_resize_size);
			if($resizeSize[1] == 0)
				$projectImg->resize($resizeSize[0]);
			else
				$projectImg->adaptiveResize($resizeSize[0], $resizeSize[1]);
				
			$projectImg->save($project_path.'/'.$this->media);
		}

		//delete other media (if media_limit = 1)
		if($setting->media_limit == 1) {
			self::model()->deleteAll(array(
				'condition'=> 'project_id = :id AND cover = :cover',
				'params'=>array(
					':id'=>$this->project_id,
					':cover'=>0,
				),
			));
		}
	}
	
	/**
	 * After delete attributes
	 */
	protected function afterDelete() {
		parent::afterDelete();
		//delete project image
		$project_path = "public/project/".$this->project_id;
		if($this->media != '')
			rename($project_path.'/'.$this->media, 'public/project/verwijderen/'.$this->project_id.'_'.$this->media);

		//reset cover in project
		$data = self::getPhoto($this->project_id);
		if($data != null) {
			if($this->cover == 1) {				
				$photo = self::model()->findByPk($data[0]->media_id);
				$photo->cover = 1;
				if($photo->update()) {
					$cover = Projects::model()->findByPk($this->project_id);
					$cover->media_id = $photo->media_id;
					$cover->update();
				}
			}
		}
	}

}