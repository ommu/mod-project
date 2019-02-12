<?php
/**
 * ProjectSetting
 *
 * This is the ActiveQuery class for [[\ommu\project\models\ProjectSetting]].
 * @see \ommu\project\models\ProjectSetting
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 11 February 2019, 14:17 WIB
 * @link https://bitbucket.org/ommu/project
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
