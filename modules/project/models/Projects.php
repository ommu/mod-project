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
 * This is the model class for table "ommu_projects".
 *
 * The followings are the available columns in table 'ommu_projects':
 * @property string $project_id
 * @property integer $publish
 * @property integer $cat_id
 * @property integer $client_id
 * @property string $user_id
 * @property string $media_id
 * @property integer $headline
 * @property integer $comment_code
 * @property string $title
 * @property string $body
 * @property string $website
 * @property integer $status
 * @property string $start_date
 * @property string $finish_date
 * @property integer $comment
 * @property integer $view
 * @property integer $likes
 * @property string $creation_date
 * @property string $modified_date
 *
 * The followings are the available model relations:
 * @property OmmuProjectLikes[] $ommuProjectLikes
 * @property OmmuProjectMedia[] $ommuProjectMedias
 * @property OmmuProjectCategory $cat
 */
class Projects extends CActiveRecord
{
	public $defaultColumns = array();
	public $client_input;
	public $team_input;
	public $media;
	public $old_media;
	public $keyword;
	public $is_website;	
	
	// Variable Search
	public $client_search;
	public $user_search;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Projects the static model class
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
		return 'ommu_projects';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cat_id, title, body, status,
				user_id,
				client_input', 'required'),
			array('publish, cat_id, client_id, headline, comment_code, status, comment, view, likes,
				is_website', 'numerical', 'integerOnly'=>true),
			array('user_id, media_id', 'length', 'max'=>11),
			array('
				team_input, keyword', 'length', 'max'=>32),
			array('
				client_input, media, old_media', 'length', 'max'=>64),
			array('title, website', 'length', 'max'=>128),
			array('
				media', 'file', 'types' => 'jpg, jpeg, png, gif', 'allowEmpty' => true),
			array('client_id, website, start_date, finish_date, creation_date, modified_date,
				client_input, team_input, media, old_media, keyword, is_website', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('project_id, publish, cat_id, client_id, user_id, media_id, headline, comment_code, title, body, website, status, start_date, finish_date, comment, view, likes, creation_date, modified_date,
				client_search, user_search', 'safe', 'on'=>'search'),
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
			'cat' => array(self::BELONGS_TO, 'ProjectCategory', 'cat_id'),
			'client' => array(self::BELONGS_TO, 'ProjectClient', 'client_id'),
			'cover' => array(self::BELONGS_TO, 'ProjectMedia', 'media_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'project_id' => 'Project',
			'publish' => 'Publish',
			'cat_id' => 'Category',
			'client_id' => 'Client',
			'user_id' => 'User',
			'media_id' => 'Media',
			'headline' => 'Headline',
			'comment_code' => 'Comment',
			'title' => 'Title',
			'body' => 'Description',
			'website' => 'Website',
			'status' => 'Status',
			'start_date' => 'Start Date',
			'finish_date' => 'Finish Date',
			'comment' => 'Comment',
			'view' => 'View',
			'likes' => 'Likes',
			'creation_date' => 'Creation Date',
			'modified_date' => 'Modified Date',
			'media' => 'Media',
			'old_media' => 'Old Media',
			'keyword' => 'Keyword',
			'user_search' => 'User',
			'is_website' => 'Website',
			'client_input' => 'Client',
			'client_search' => 'Client',
			'team_input' => 'Team',
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

		$criteria->compare('t.project_id',$this->project_id);
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
		if(isset($_GET['category'])) {
			$criteria->compare('t.cat_id',$_GET['category']);
		} else {
			$criteria->compare('t.cat_id',$this->cat_id);
		}
		if(isset($_GET['client'])) {
			$criteria->compare('t.client_id',$_GET['client']);
		} else {
			$criteria->compare('t.client_id',$this->client_id);
		}
		if(isset($_GET['user'])) {
			$criteria->compare('t.user_id',$_GET['user']);
		} else {
			$criteria->compare('t.user_id',$this->user_id);
		}
		$criteria->compare('t.media_id',$this->media_id);
		$criteria->compare('t.headline',$this->headline);
		$criteria->compare('t.comment_code',$this->comment_code);
		$criteria->compare('t.title',strtolower($this->title),true);
		$criteria->compare('t.body',strtolower($this->body),true);
		$criteria->compare('t.website',strtolower($this->website),true);
		if(isset($_GET['status'])) {
			$criteria->compare('t.status',$_GET['status']);
		} else {
			$criteria->compare('t.status',$this->status);
		}
		$criteria->compare('t.start_date',strtolower($this->start_date),true);
		$criteria->compare('t.finish_date',strtolower($this->finish_date),true);
		$criteria->compare('t.comment',$this->comment);
		$criteria->compare('t.view',$this->view);
		$criteria->compare('t.likes',$this->likes);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.creation_date)',date('Y-m-d', strtotime($this->creation_date)));
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.modified_date)',date('Y-m-d', strtotime($this->modified_date)));
		
		// Custom Search
		$criteria->with = array(
			'client' => array(
				'alias'=>'client',
				'select'=>'client_name'
			),
			'user' => array(
				'alias'=>'user',
				'select'=>'displayname'
			),
		);
		$criteria->compare('client.client_name',strtolower($this->client_search), true);
		$criteria->compare('user.displayname',strtolower($this->user_search), true);

		if(!isset($_GET['Projects_sort']))
			$criteria->order = 'project_id DESC';

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
			//$this->defaultColumns[] = 'project_id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'cat_id';
			$this->defaultColumns[] = 'client_id';
			$this->defaultColumns[] = 'user_id';
			$this->defaultColumns[] = 'media_id';
			$this->defaultColumns[] = 'headline';
			$this->defaultColumns[] = 'comment_code';
			$this->defaultColumns[] = 'title';
			$this->defaultColumns[] = 'body';
			$this->defaultColumns[] = 'website';
			$this->defaultColumns[] = 'status';
			$this->defaultColumns[] = 'start_date';
			$this->defaultColumns[] = 'finish_date';
			$this->defaultColumns[] = 'comment';
			$this->defaultColumns[] = 'view';
			$this->defaultColumns[] = 'likes';
			$this->defaultColumns[] = 'creation_date';
			$this->defaultColumns[] = 'modified_date';
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
			$this->defaultColumns[] = array(
				'name' => 'title',
				'value' => '$data->title."<br/><span>".Utility::shortText(Utility::hardDecode($data->body),200)."</span>"',
				'htmlOptions' => array(
					'class' => 'bold',
				),
				'type' => 'raw',
			);
			if(!isset($_GET['category'])) {
				$this->defaultColumns[] = array(
					'name' => 'cat_id',
					'value' => '$data->cat->name',
					'filter'=> ProjectCategory::getCategory(),
					'type' => 'raw',
				);
			}
			if(!isset($_GET['client'])) {
				$this->defaultColumns[] = array(
					'name' => 'client_search',
					'value' => '$data->client->client_name',
				);
			}
			$this->defaultColumns[] = array(
				'name' => 'start_date',
				'value' => 'Utility::dateFormat($data->start_date)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'=>$this, 
					'attribute'=>'start_date', 
					'language' => 'ja',
					'i18nScriptFile' => 'jquery.ui.datepicker-en.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'start_date_filter',
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
				'name' => 'finish_date',
				'value' => 'Utility::dateFormat($data->finish_date)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'=>$this, 
					'attribute'=>'finish_date', 
					'language' => 'ja',
					'i18nScriptFile' => 'jquery.ui.datepicker-en.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'finish_date_filter',
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
			if(!isset($_GET['status'])) {
				$this->defaultColumns[] = array(
					'name' => 'status',
					'value' => '$data->status != 0 ? ($data->status == 1 ? "Process" : "Done") : "Waiting"',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter'=>array(
						2=>'Done',
						1=>'Process',
						0=>'Waiting',
					),
					'type' => 'raw',
				);
			}
			if(OmmuSettings::getInfo('site_headline') == 1) {
				$this->defaultColumns[] = array(
					'name' => 'headline',
					'value' => '$data->headline == 1 ? Chtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : Utility::getPublish(Yii::app()->controller->createUrl("headline",array("id"=>$data->project_id)), $data->headline, 9)',
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
			if(!isset($_GET['type'])) {
				$this->defaultColumns[] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("publish",array("id"=>$data->project_id)), $data->publish, 1)',
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
	 * User get information
	 */
	public static function getInfo($id=1, $column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk($id,array(
				'select' => $column
			));
			return $model->$column;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;			
		}
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() {
		if(parent::beforeValidate()) {
			if($this->status != 0 && $this->start_date == '') {
				$this->addError('start_date', 'Start Date cannot be blank.');
			}
			if($this->status == 2 && $this->finish_date == '') {
				$this->addError('finish_date', 'Finish Date cannot be blank.');
			}
			if($this->isNewRecord) {
				$this->user_id = Yii::app()->user->id;
			}
			if($this->headline == 1 && $this->publish == 0) {
				$this->addError('publish', 'Publish cannot be blank.');
			}
			if($this->is_website == 1 && $this->website == '') {
				$this->addError('website', 'Website cannot be blank.');
			}
			if($this->is_website == 0) {
				$this->website = '';
			}
		}
		return true;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() {
		if(parent::beforeSave()) {
			$this->start_date = date('Y-m-d', strtotime($this->start_date));
			$this->finish_date = date('Y-m-d', strtotime($this->finish_date));
		}
		return true;
	}
	
	/**
	 * After save attributes
	 */
	protected function afterSave() {
		parent::afterSave();

		$project_path = "public/project/".$this->project_id;

		if($this->isNewRecord) {
			// Add project directory
			if(!file_exists($project_path)) {
				@mkdir($project_path, 0777, true);

				// Add file in project directory (index.php)
				$newFile = $project_path.'/index.php';
				$FileHandle = fopen($newFile, 'w');
			}
		}

		$this->media = CUploadedFile::getInstance($this, 'media');
		if($this->media instanceOf CUploadedFile) {
			$fileName = time().'_'.$this->project_id.'.'.strtolower($this->media->extensionName);
			if($this->media->saveAs($project_path.'/'.$fileName)) {
				if($this->isNewRecord || (!$this->isNewRecord && ProjectSetting::getInfo('media_limit') == 1 && $this->media_id == 0)) {
					$images = new ProjectMedia;
					$images->project_id = $this->project_id;
					$images->cover = 1;
					$images->media = $fileName;
					$images->save();
				} else {
					rename($project_path.'/'.$this->old_media, 'public/project/verwijderen/'.$this->project_id.'_'.$this->old_media);
					$images = ProjectMedia::model()->findByPk($this->media_id);
					$images->media = $fileName;
					$images->update();
				}
			}
		}
		
		// Add Keyword
		if(!$this->isNewRecord) {
			if($this->keyword != '') {
				$model = OmmuTags::model()->find(array(
					'select' => 'tag_id, body',
					'condition' => 'publish = 1 AND body = :body',
					'params' => array(
						':body' => $this->keyword,
					),
				));
				$tag = new ProjectTag;
				$tag->project_id = $this->project_id;
				if($model != null) {
					$tag->tag_id = $model->tag_id;
				} else {
					$data = new OmmuTags;
					$data->body = $this->keyword;
					if($data->save()) {
						$tag->tag_id = $data->tag_id;
					}
				}
				$tag->save();
			}			
		}

		// Reset headline
		if(ProjectSetting::getInfo('headline') == 1) {
			if($this->headline == 1) {
				self::model()->updateAll(array(
					'headline' => 0,	
				), array(
					'condition'=> 'project_id != :id',
					'params'=>array(
						':id'=>$this->project_id,
					),
				));
			}
		} else {
			
		}
	}

	/**
	 * Before delete attributes
	 */
	protected function beforeDelete() {
		if(parent::beforeDelete()) {
			$project_photo = ProjectMedia::getPhoto($this->project_id);
			$project_path = "public/project/".$this->project_id;
			foreach($project_path as $val) {
				if($val->media != '')
					rename($project_path.'/'.$val->media, 'public/project/verwijderen/'.$val->project_id.'_'.$val->media);
			}
		}
		return true;
	}

	/**
	 * After delete attributes
	 */
	protected function afterDelete() {
		parent::afterDelete();
		//delete project image
		$project_path = "public/project/".$this->project_id;
		Utility::deleteFolder($project_path);
	}

}