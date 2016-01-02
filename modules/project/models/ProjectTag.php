<?php

/**
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
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
 * This is the model class for table "ommu_project_tag".
 *
 * The followings are the available columns in table 'ommu_project_tag':
 * @property string $id
 * @property string $project_id
 * @property string $tag_id
 * @property string $creation_date
 *
 * The followings are the available model relations:
 * @property OmmuProjects $project
 */
class ProjectTag extends CActiveRecord
{
	public $defaultColumns = array();
	public $body;
	
	// Variable Search
	public $project_search;
	public $tag_search;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProjectTag the static model class
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
		return 'ommu_project_tag';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('project_id, tag_id', 'required'),
			array('project_id, tag_id', 'length', 'max'=>11),
			array('creation_date, 
				body', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, project_id, tag_id, creation_date,
				project_search, tag_search', 'safe', 'on'=>'search'),
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
			'tag' => array(self::BELONGS_TO, 'OmmuTags', 'tag_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'project_id' => 'Project',
			'tag_id' => 'Tags',
			'creation_date' => 'Creation Date',
			'project_search' => 'Project',
			'tag_search' => 'Tags',
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

		$criteria->compare('t.id',$this->id,true);
		if(isset($_GET['project'])) {
			$criteria->compare('t.project_id',$_GET['project']);
		} else {
			$criteria->compare('t.project_id',$this->project_id);
		}
		$criteria->compare('t.tag_id',$this->tag_id,true);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.creation_date)',date('Y-m-d', strtotime($this->creation_date)));
		
		// Custom Search
		$criteria->with = array(
			'project' => array(
				'alias'=>'project',
				'select'=>'title'
			),
			'tag' => array(
				'alias'=>'tag',
				'select'=>'body'
			),
		);
		$criteria->compare('project.title',strtolower($this->project_search), true);
		$criteria->compare('tag.body',strtolower($this->tag_search), true);

		if(!isset($_GET['ProjectTag_sort']))
			$criteria->order = 'id DESC';

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
		}else {
			//$this->defaultColumns[] = 'id';
			$this->defaultColumns[] = 'project_id';
			$this->defaultColumns[] = 'tag_id';
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
				'name' => 'tag_search',
				'value' => '$data->tag->body',
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
		}
		parent::afterConstruct();
	}

	/**
	 * get project tag
	 */
	public static function getKeyword($keyword, $id) {
		$model = self::model()->findAll(array(
			'condition' => 'project_id = :id',
			'params' => array(
				':id' => $id,
			),
			'order' => 'id DESC',
			'limit' => 30,
		));
		
		$tag = '';
		if($model != null) {
			foreach($model as $val) {
				$tag .= ','.$val->tag->body;
			}
		}
		
		return $keyword.$tag;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() {
		if(parent::beforeSave()) {
			if($this->isNewRecord) {
				if($this->tag_id == 0) {
					$tag = OmmuTags::model()->find(array(
						'select' => 'tag_id, body',
						'condition' => 'publish = 1 AND body = :body',
						'params' => array(
							':body' => $this->body,
						),
					));
					if($tag != null) {
						$this->tag_id = $tag->tag_id;
					} else {
						$data = new OmmuTags;
						$data->body = $this->body;
						if($data->save()) {
							$this->tag_id = $data->tag_id;
						}
					}					
				}
			}
		}
		return true;
	}

}