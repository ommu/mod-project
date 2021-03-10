<?php
/**
 * Projects
 *
 * Projects represents the model behind the search form about `ommu\project\models\Projects`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 7 February 2019, 19:54 WIB
 * @modified date 8 February 2019, 11:23 WIB
 * @link https://bitbucket.org/ommu/project
 *
 */

namespace ommu\project\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\project\models\Projects as ProjectsModel;

class Projects extends ProjectsModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['project_id', 'publish', 'cat_id', 'company_id', 'headline', 'comment', 'creation_id', 'modified_id'], 'integer'],
			[['project_name', 'project_desc', 'status', 'start_date', 'finish_date', 'headline_date', 'creation_date', 'modified_date', 'updated_date', 'slug',
				'categoryName', 'companyName', 'creationDisplayname', 'modifiedDisplayname'], 'safe'],
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
        if (!($column && is_array($column))) {
            $query = ProjectsModel::find()->alias('t');
        } else {
            $query = ProjectsModel::find()->alias('t')->select($column);
        }
		$query->joinWith([
			'category.title category', 
			'company company', 
			'creation creation', 
			'modified modified'
		]);

		$query->groupBy(['project_id']);

        // add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
        // disable pagination agar data pada api tampil semua
        if (isset($params['pagination']) && $params['pagination'] == 0) {
            $dataParams['pagination'] = false;
        }
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['cat_id'] = [
			'asc' => ['category.message' => SORT_ASC],
			'desc' => ['category.message' => SORT_DESC],
		];
		$attributes['categoryName'] = [
			'asc' => ['category.message' => SORT_ASC],
			'desc' => ['category.message' => SORT_DESC],
		];
		$attributes['companyName'] = [
			'asc' => ['company.company_name' => SORT_ASC],
			'desc' => ['company.company_name' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['project_id' => SORT_DESC],
		]);

		$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		// grid filtering conditions
		$query->andFilterWhere([
			't.project_id' => $this->project_id,
			't.cat_id' => isset($params['category']) ? $params['category'] : $this->cat_id,
			't.company_id' => isset($params['company']) ? $params['company'] : $this->company_id,
			't.status' => $this->status,
			'cast(t.start_date as date)' => $this->start_date,
			'cast(t.finish_date as date)' => $this->finish_date,
			't.headline' => $this->headline,
			't.comment' => $this->comment,
			'cast(t.headline_date as date)' => $this->headline_date,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
		]);

        if (isset($params['trash'])) {
            $query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
        } else {
            if (!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == '')) {
                $query->andFilterWhere(['IN', 't.publish', [0,1]]);
            } else {
                $query->andFilterWhere(['t.publish' => $this->publish]);
            }
        }

		$query->andFilterWhere(['like', 't.project_name', $this->project_name])
			->andFilterWhere(['like', 't.project_desc', $this->project_desc])
			->andFilterWhere(['like', 't.slug', $this->slug])
			->andFilterWhere(['like', 'category.message', $this->categoryName])
			->andFilterWhere(['like', 'company.company_name', $this->companyName])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
