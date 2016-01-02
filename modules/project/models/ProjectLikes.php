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
 * This is the model class for table "ommu_project_likes".
 *
 * The followings are the available columns in table 'ommu_project_likes':
 * @property string $like_id
 * @property string $project_id
 * @property string $user_id
 * @property string $likes_date
 * @property string $likes_ip
 *
 * The followings are the available model relations:
 * @property OmmuProjects $project
 */
class ProjectLikes extends CActiveRecord
{
	public $defaultColumns = array();
	
	// Variable Search
	public $project_search;
	public $user_search;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProjectLikes the static model class
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
		return 'ommu_project_likes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('project_id,
				user_id, likes_ip', 'required'),
			array('project_id, user_id', 'length', 'max'=>11),
			array('likes_ip', 'length', 'max'=>20),
			array('likes_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('like_id, project_id, user_id, likes_date, likes_ip,
				project_search, user_search', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'like_id' => 'Likes',
			'project_id' => 'Project',
			'user_id' => 'User',
			'likes_date' => 'Likes Date',
			'likes_ip' => 'Likes Ip',
			'project_search' => 'Project',
			'user_search' => 'User',
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

		$criteria->compare('t.like_id',$this->like_id);
		if(isset($_GET['project'])) {
			$criteria->compare('t.project_id',$_GET['project']);
		} else {
			$criteria->compare('t.project_id',$this->project_id);
		}
		$criteria->compare('t.user_id',$this->user_id);
		if($this->likes_date != null && !in_array($this->likes_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.likes_date)',date('Y-m-d', strtotime($this->likes_date)));
		$criteria->compare('t.likes_ip',strtolower($this->likes_ip),true);
		
		// Custom Search
		$criteria->with = array(
			'project' => array(
				'alias'=>'project',
				'select'=>'title'
			),
			'user' => array(
				'alias'=>'user',
				'select'=>'displayname'
			),
		);
		$criteria->compare('project.title',strtolower($this->project_search), true);
		$criteria->compare('user.displayname',strtolower($this->user_search), true);

		if(!isset($_GET['ProjectLikes_sort']))
			$criteria->order = 'like_id DESC';

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
			//$this->defaultColumns[] = 'like_id';
			$this->defaultColumns[] = 'project_id';
			$this->defaultColumns[] = 'user_id';
			$this->defaultColumns[] = 'likes_date';
			$this->defaultColumns[] = 'likes_ip';
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
				'name' => 'user_search',
				'value' => '$data->user->displayname',
			);
			$this->defaultColumns[] = array(
				'name' => 'likes_date',
				'value' => 'Utility::dateFormat($data->likes_date)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'=>$this, 
					'attribute'=>'likes_date', 
					'language' => 'ja',
					'i18nScriptFile' => 'jquery.ui.datepicker-en.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'likes_date_filter',
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
				'name' => 'likes_ip',
				'value' => '$data->likes_ip',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
		}
		parent::afterConstruct();
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() {
		if(parent::beforeValidate()) {		
			if($this->isNewRecord) {
				$this->user_id = Yii::app()->user->id;
				$this->likes_ip = $_SERVER['REMOTE_ADDR'];
			}		
		}
		return true;
	}
}