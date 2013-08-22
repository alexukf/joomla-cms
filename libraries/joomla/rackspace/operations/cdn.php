<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Rackspace
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Common items for operations on the service
 *
 * @package     Joomla.Platform
 * @subpackage  Rackspace
 * @since       ??.?
 */
class JRackspaceOperationsCdn extends JRackspaceObject
{
	/**
	 * @var    JRackspaceOperationsCdnAccount  Rackspace API object for CDN Account Services
	 * @since  ??.?
	 */
	protected $account;

	/**
	 * @var    JRackspaceOperationsCdnContainer Rackspace API object for CDN Container Services
	 * @since  ??.?
	 */
	protected $container;

	/**
	 * @var    JRackspaceOperationsCdnObject  Rackspace API object for CDN Object Services
	 * @since  ??.?
	 */
	protected $object;

	/**
	 * @var    JRackspaceOperationsCdnStaticweb  Rackspace API object for CDN Static Web  Services
	 * @since  ??.?
	 */
	protected $staticweb;

	/**
	 * Magic method to lazily create API objects
	 *
	 * @param   string  $name  Name of property to retrieve.
	 *
	 * @return  JRackspaceObject  Rackspace API object
	 *
	 * @since   ??.?
	 * @throws  InvalidArgumentException
	 */
	public function __get($name)
	{
		$class = 'JRackspaceOperationsCdn' . ucfirst($name);

		if (class_exists($class))
		{
			if (false == isset($this->$name))
			{
				$this->$name = new $class($this->options, $this->client);
			}

			return $this->$name;
		}

		throw new InvalidArgumentException(
			sprintf('Argument %s produced an invalid class name: %s', $name, $class)
		);
	}
}
