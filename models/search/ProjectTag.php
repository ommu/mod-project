<?php
/**
 * ProjectTag
 *
 * ProjectTag represents the model behind the search form about `ommu\project\models\ProjectTag`.
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
use ommu\project\models\ProjectTag as ProjectTagModel;

class ProjectTag extends ProjectTagModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'project_id', 'tag_id', 'creation_id'], 'integer'],
			[['creation_date', 
				'categoryId', 'tagBody', 'projectName', 'creationDisplayname'], 'safe'],
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
	public function search($params, $column=null)
	{
		if(!($column && is_array($column)))
			$query = ProjectTagModel::find()->alias('t');
		else
			$query = ProjectTagModel::find()->alias('t')->select($column);
		$query->joinWith([
			'tag tag', 
			'project project', 
			'creation creation',
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
		$attributes['tagBody'] = [
			'asc' => ['tag.body' => SORT_ASC],
			'desc' => ['tag.body' => SORT_DESC],
		];
		$attributes['projectName'] = [
			'asc' => ['project.project_name' => SORT_ASC],
			'desc' => ['project.project_name' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['categoryId'] = [
			'asc' => ['category.message' => SORT_ASC],
			'desc' => ['category.message' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

		$this->load($params);

		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.id' => $this->id,
			't.project_id' => isset($params['project']) ? $params['project'] : $this->project_id,
			't.tag_id' => isset($params['tag']) ? $params['tag'] : $this->tag_id,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'project.cat_id' => isset($params['category']) ? $params['category'] : $this->categoryId,
		]);

		$query->andFilterWhere(['like', 'tag.body', $this->tagBody])
			->andFilterWhere(['like', 'project.project_name', $this->projectName])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname]);

		return $dataProvider;
	}
}
