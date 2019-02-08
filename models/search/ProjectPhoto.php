<?php
/**
 * ProjectPhoto
 *
 * ProjectPhoto represents the model behind the search form about `ommu\project\models\ProjectPhoto`.
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 8 February 2019, 15:33 WIB
 * @link https://bitbucket.org/ommu/project
 *
 */

namespace ommu\project\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\project\models\ProjectPhoto as ProjectPhotoModel;

class ProjectPhoto extends ProjectPhotoModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['photo_id', 'publish', 'cover', 'project_id', 'creation_id', 'modified_id'], 'integer'],
			[['photo', 'photo_title', 'photo_caption', 'creation_date', 'modified_date', 'updated_date', 
				'categoryId', 'projectName', 'creationDisplayname', 'modifiedDisplayname'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Tambahkan fungsi beforeValidate ini pada model search untuk menumpuk validasi pd model induk. 
	 * dan "jangan" tambahkan parent::beforeValidate, cukup "return true" saja.
	 * maka validasi yg akan dipakai hanya pd model ini, semua script yg ditaruh di beforeValidate pada model induk
	 * tidak akan dijalankan.
	 */
	public function beforeValidate() {
		return true;
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = ProjectPhotoModel::find()->alias('t');
		$query->joinWith([
			'project project', 
			'creation creation', 
			'modified modified',
			'project.category.title category',
		]);

		// add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
		// disable pagination agar data pada api tampil semua
		if(isset($params['pagination']) && $params['pagination'] == 0)
			$dataParams['pagination'] = false;
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['projectName'] = [
			'asc' => ['project.project_name' => SORT_ASC],
			'desc' => ['project.project_name' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$attributes['categoryId'] = [
			'asc' => ['category.message' => SORT_ASC],
			'desc' => ['category.message' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['photo_id' => SORT_DESC],
		]);

		$this->load($params);

		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.photo_id' => $this->photo_id,
			't.cover' => $this->cover,
			't.project_id' => isset($params['project']) ? $params['project'] : $this->project_id,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
			'project.cat_id' => isset($params['category']) ? $params['category'] : $this->categoryId,
		]);

		if(isset($params['trash']))
			$query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
		else {
			if(!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == ''))
				$query->andFilterWhere(['IN', 't.publish', [0,1]]);
			else
				$query->andFilterWhere(['t.publish' => $this->publish]);
		}

		$query->andFilterWhere(['like', 't.photo', $this->photo])
			->andFilterWhere(['like', 't.photo_title', $this->photo_title])
			->andFilterWhere(['like', 't.photo_caption', $this->photo_caption])
			->andFilterWhere(['like', 'project.project_name', $this->projectName])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
