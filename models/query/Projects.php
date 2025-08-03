<?php
/**
 * Projects
 *
 * This is the ActiveQuery class for [[\ommu\project\models\Projects]].
 * @see \ommu\project\models\Projects
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 7 February 2019, 17:57 WIB
 * @link https://github.com/ommu/mod-project
 *
 */

namespace ommu\project\models\query;

class Projects extends \yii\db\ActiveQuery
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
	 */
	public function headlined()
	{
		return $this->andWhere(['t.publish' => 1])
			->andWhere(['headline' => 1]);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\project\models\Projects[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\project\models\Projects|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
