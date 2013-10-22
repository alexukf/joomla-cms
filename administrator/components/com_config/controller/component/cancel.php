<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_config
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Cancel Controller for global configuration components
 *
 * @package     Joomla.Administrator
 * @subpackage  com_config
 * @since       3.2
*/
class ConfigControllerComponentCancel extends ConfigControllerCanceladmin
{
	/**
	 * Method to cancel global configuration component.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function execute()
	{
		$this->context = 'com_config.config.global';
		$returnUri = $this->input->post->get('return', null, 'base64');
		$redirect = 'index.php?option=' . $this->component;

		if (!empty($returnUri))
		{
			$redirect = base64_decode($returnUri);
		}

		$this->redirect = $redirect;

		parent::execute();
	}
}
