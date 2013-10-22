<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Dropbox
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Defines the POST operations on files
 *
 * @package     Joomla.Platform
 * @subpackage  Dropbox
 * @since       ??.?
 */
class JDropboxFilesPost extends JDropboxFiles
{
	/**
	 * Uploads a file using POST semantics
	 *
	 * @param   string  $root    The root relative to which path is specified. Valid values are sandbox and dropbox.
	 * @param   string  $path    The path to the file you want to retrieve.
	 * @param   string  $data    The file contents to be uploaded.
	 * @param   array   $params  The parameters to be used in the request.
	 *
	 * @return string  The response body
	 *
	 * @since   ??.?
	 */
	public function postFiles($root, $path, $data, $params = array())
	{
		$url = "https://" . $this->options->get("api.content") . "/1/files/" . $root . "/" . $path;
		$paramsString = "";

		foreach ($params as $key => $param)
		{
			$paramsString .= "&" . $key . "=" . $param;
		}

		if (! empty($params))
		{
			$paramsString[0] = "?";
			$url .= $paramsString;
		}

		// Creates an array with the default Host and Authorization headers
		$headers = $this->getDefaultHeaders();

		// Send the http request
		$response = $this->client->post($url, $data, $headers);

		// Process the response
		return $this->processResponse($response);
	}
}
