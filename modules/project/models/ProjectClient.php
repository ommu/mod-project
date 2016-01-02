<?php
/**
 * ProjectClient * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
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
 * This is the model class for table "ommu_project_client".
 *
 * The followings are the available columns in table 'ommu_project_client':
 * @property integer $client_id
 * @property integer $publish
 * @property string $client_name
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property string $cp_name
 * @property string $creation_date
 *
 * The followings are the available model relations:
 * @property OmmuProjects[] $ommuProjects
 */
class ProjectClient extends CActiveRecord
{
	public $defaultColumns = array();
	public $project_count;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProjectClient the static model class
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
		return 'ommu_project_client';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client_name', 'required'),
			array('address, phone, email, cp_name', 'required', 'on'=>'edit'),
			array('publish', 'numerical', 'integerOnly'=>true),
			array('client_name', 'length', 'max'=>64),
			array('phone, email, cp_name', 'length', 'max'=>32),
			array('
				project_count', 'length', 'max'=>5),
			array('
				project_count', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('client_id, publish, client_name, address, phone, email, cp_name, creation_date', 'safe', 'on'=>'search'),
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
			'ommuProjects' => array(self::HAS_MANY, 'OmmuProjects', 'client_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'client_id' => 'Client',
			'publish' => 'Publish',
			'client_name' => 'Client Name',
			'address' => 'Address',
			'phone' => 'Phone',
			'email' => 'Email',
			'cp_name' => 'Contact Person',
			'creation_date' => 'Creation Date',
			'project_count' => 'Modified Date',
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

		$criteria->compare('t.client_id',$this->client_id);
		if(isset($_GET['type']) && $_GET['type'] == 'publish') {
			$criteria->compare('t.publish',1);
		} elseif(isset($_GET['type']) && $_GET['type'] == 'unpublish') {
			$criteria->compare('t.publish',0);
		} elseif(isset($_GET['type']) && $_GET['type'] == 'trash') {
			$criteria->compare('t.publish',2);
		} else {
			$criteria->addInCondition('t.publish',array(0,1));
			$criteria->compare('t.publish',$this->publish);
		}
		$criteria->compare('t.client_name',$this->client_name,true);
		$criteria->compare('t.address',$this->address,true);
		$criteria->compare('t.phone',$this->phone,true);
		$criteria->compare('t.email',$this->email,true);
		$criteria->compare('t.cp_name',$this->cp_name,true);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.creation_date)',date('Y-m-d', strtotime($this->creation_date)));		
		$criteria->compare('t.project_count',$this->project_count);
		
		if(!isset($_GET['ProjectClient_sort']))
			$criteria->order = 'client_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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
			//$this->defaultColumns[] = 'client_id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'client_name';
			$this->defaultColumns[] = 'address';
			$this->defaultColumns[] = 'phone';
			$this->defaultColumns[] = 'email';
			$this->defaultColumns[] = 'cp_name';
			$this->defaultColumns[] = 'creation_date';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->defaultColumns) == 0) {
			/*
			$this->defaultColumns[] = array(
				'class' => 'CCheckBoxColumn',
				'name' => 'id',
				'selectableRows' => 2,
				'checkBoxHtmlOptions' => array('name' => 'trash_id[]')
			);
			*/
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			$this->defaultColumns[] = 'client_name';
			$this->defaultColumns[] = 'address';
			$this->defaultColumns[] = 'cp_name';
			$this->defaultColumns[] = 'phone';
			$this->defaultColumns[] = 'email';
			$this->defaultColumns[] = array(
				'header' => 'project_count',
				'value' => 'CHtml::link($data->project_count." Project", Yii::app()->controller->createUrl("admin/manage",array("client"=>$data->client_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
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
			if(!isset($_GET['type'])) {
				$this->defaultColumns[] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("publish",array("id"=>$data->client_id)), $data->publish, 1)',
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

		}
		parent::afterConstruct();
	}

	/**
	 * Get Item
	 */
	public static function getClientProject($id, $type=null) {
		$criteria=new CDbCriteria;
		$criteria->compare('client_id',$id);
		
		if($type == null) {
			//$criteria->select = '';
			$model = Projects::model()->findAll($criteria);
		} else {
			$model = Projects::model()->count($criteria);
		}
		
		return $model;
	}
	
	protected function afterFind() {
		$this->project_count = self::getClientProject($this->client_id, 'count');
		parent::afterFind();
	}
}