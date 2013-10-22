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
 * Defines the POST file operations
 *
 * @package     Joomla.Platform
 * @subpackage  Dropbox
 * @since       ??.?
 */
class JDropboxFileopsPost extends JDropboxFiles
{
	/**
	 * Copies a file or folder to a new location.
	 *
	 * @param   array  $params  The parameters to be used in the request.
	 *                          "root" and "to_path" are required parameters.
	 *
	 * @return string  The response body
	 *
	 * @since   ??.?
	 */
	public function postCopy($params)
	{
		$url = "https://" . $this->options->get("api.url") . "/1/fileops/copy";
		$url .= $this->createParamsString($params);

		// Creates an array with the default Host and Authorization headers
		$headers = $this->getDefaultHeaders();

		// Send the http request
		$response = $this->client->post($url, "", $headers);

		// Process the response
		return $this->processResponse($response);
	}

	/**
	 * Creates a folder.
	 *
	 * @param   array  $params  The parameters to be used in the request.
	 *                          "root" and "path" are required parameters.
	 *
	 * @return string  The response body
	 *
	 * @since   ??.?
	 */
	public function postCreateFolder($params)
	{
		$url = "https://" . $this->options->get("api.url") . "/1/fileops/create_folder";
		$url .= $this->createParamsString($params);

		// Creates an array with the default Host and Authorization headers
		$headers = $this->getDefaultHeaders();

		// Send the http request
		$response = $this->client->post($url, "", $headers);

		// Process the response
		return $this->processResponse($response);
	}

	/**
	 * Deletes a file or folder.
	 *
	 * @param   array  $params  The parameters to be used in the request.
	 *                          "root" and "path" are required parameters.
	 *
	 * @return string  The response body
	 *
	 * @since   ??.?
	 */
	public function postDelete($params)
	{
		$url = "https://" . $this->options->get("api.url") . "/1/fileops/delete";
		$url .= $this->createParamsString($params);

		// Creates an array with the default Host and Authorization headers
		$headers = $this->getDefaultHeaders();

		// Send the http request
		$response = $this->client->post($url, "", $headers);

		// Process the response
		return $this->processResponse($response);
	}

	/**
	 * Moves a file or folder to a new location.
	 *
	 * @param   array  $params  The parameters to be used in the request.
	 *                          "root", "from_path" and "to_path" are required parameters.
	 *
	 * @return string  The response body
	 *
	 * @since   ??.?
	 */
	public function postMove($params)
	{
		$url = "https://" . $this->options->get("api.url") . "/1/fileops/move";
		$url .= $this->createParamsString($params);

		// Creates an array with the default Host and Authorization headers
		$headers = $this->getDefaultHeaders();

		// Send the http request
		$response = $this->client->post($url, "", $headers);

		// Process the response
		return $this->processResponse($response);
	}
}
