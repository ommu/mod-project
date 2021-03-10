<?php
/**
 * ProjectSetting
 *
 * ProjectSetting represents the model behind the search form about `ommu\project\models\ProjectSetting`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 11 February 2019, 14:19 WIB
 * @link https://bitbucket.org/ommu/project
 *
 */

namespace ommu\project\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\project\models\ProjectSetting as ProjectSettingModel;

class ProjectSetting extends ProjectSettingModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'permission', 'headline', 'headline_limit', 'photo_limit', 'photo_resize', 'modified_id'], 'integer'],
			[['license', 'meta_description', 'meta_keyword', 'headline_category', 'photo_resize_size', 'photo_view_size', 'photo_file_type', 'modified_date', 'modifiedDisplayname'], 'safe'],
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
            $query = ProjectSettingModel::find()->alias('t');
        } else {
            $query = ProjectSettingModel::find()->alias('t')->select($column);
        }
		$query->joinWith([
			'modified modified'
		]);

		$query->groupBy(['id']);

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
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

        if (Yii::$app->request->get('id')) {
            unset($params['id']);
        }
		$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		// grid filtering conditions
		$query->andFilterWhere([
			't.id' => $this->id,
			't.permission' => $this->permission,
			't.headline' => $this->headline,
			't.headline_limit' => $this->headline_limit,
			't.photo_limit' => $this->photo_limit,
			't.photo_resize' => $this->photo_resize,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
		]);

		$query->andFilterWhere(['like', 't.license', $this->license])
			->andFilterWhere(['like', 't.meta_description', $this->meta_description])
			->andFilterWhere(['like', 't.meta_keyword', $this->meta_keyword])
			->andFilterWhere(['like', 't.headline_category', $this->headline_category])
			->andFilterWhere(['like', 't.photo_resize_size', $this->photo_resize_size])
			->andFilterWhere(['like', 't.photo_view_size', $this->photo_view_size])
			->andFilterWhere(['like', 't.photo_file_type', $this->photo_file_type])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
