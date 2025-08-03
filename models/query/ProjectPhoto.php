<?php
/**
 * ProjectPhoto
 *
 * This is the ActiveQuery class for [[\ommu\project\models\ProjectPhoto]].
 * @see \ommu\project\models\ProjectPhoto
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 8 February 2019, 11:58 WIB
 * @link https://github.com/ommu/mod-project
 *
 */

namespace ommu\project\models\query;

class ProjectPhoto extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 */
	public function published()
	{
		return $this->andWhere(['t.publish' => 1]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function unpublish()
	{
		return $this->andWhere(['t.publish' => 0]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleted()
	{
		return $this->andWhere(['t.publish' => 2]);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\project\models\ProjectPhoto[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\project\models\ProjectPhoto|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
