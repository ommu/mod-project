<?php
/**
 * project module definition class
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 7 February 2019, 17:36 WIB
 * @link https://bitbucket.org/ommu/project
 *
 */

namespace ommu\project;

class Module extends \app\components\Module
{
	public $layout = 'main';

	/**
	 * {@inheritdoc}
	 */
	public $controllerNamespace = 'ommu\project\controllers';

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();

		// custom initialization code goes here
	}
}
