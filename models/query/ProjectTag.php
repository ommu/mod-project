<?php
/**
 * ProjectTag
 *
 * This is the ActiveQuery class for [[\ommu\project\models\ProjectTag]].
 * @see \ommu\project\models\ProjectTag
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 8 February 2019, 11:58 WIB
 * @link https://bitbucket.org/ommu/project
 *
 */

namespace ommu\project\models\query;

class ProjectTag extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\project\models\ProjectTag[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\project\models\ProjectTag|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
