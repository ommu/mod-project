<?php
/**
 * ProjectSetting
 *
 * This is the ActiveQuery class for [[\ommu\project\models\ProjectSetting]].
 * @see \ommu\project\models\ProjectSetting
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 11 February 2019, 14:17 WIB
 * @link https://github.com/ommu/mod-project
 *
 */

namespace ommu\project\models\query;

class ProjectSetting extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\project\models\ProjectSetting[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\project\models\ProjectSetting|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
