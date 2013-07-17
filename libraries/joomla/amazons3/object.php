<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Amazons3
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Amazons3 API object class for the Joomla Platform.
 *
 * @package     Joomla.Platform
 * @subpackage  Amazons3
 * @since       ??.?
 */
abstract class JAmazons3Object
{
	/**
	 * @var    JRegistry  Options for the Amazons3 object.
	 * @since  ??.?
	 */
	protected $options;

	/**
	 * @var    JAmazons3Http  The HTTP client object to use in sending HTTP requests.
	 * @since  ??.?
	 */
	protected $client;

	/**
	 * Constructor.
	 *
	 * @param   JRegistry      $options  Amazons3 options object.
	 * @param   JAmazons3Http  $client   The HTTP client object.
	 *
	 * @since   ??.?
	 */
	public function __construct(JRegistry $options = null, JAmazons3Http $client = null)
	{
		$this->options = isset($options) ? $options : new JRegistry;
		$this->client = isset($client) ? $client : new JAmazons3Http($this->options);
	}

	/**
	 * Common operations performed by all of the methods that send GET requests
	 *
	 * @param   string  $url  The url that is used in the request
	 *
	 * @return string  The response body
	 *
	 * @since   ??.?
	 */
	public function commonGetOperations($url)
	{
		$headers = array(
			"Date" => date("D, d M Y H:i:s O"),
		);
		$authorization = $this->createAuthorization("GET", $url, $headers);
		$headers["Authorization"] = $authorization;

		// Send the http request
		$response = $this->client->get($url, $headers);

		// Process the response
		$response_body = $this->processResponse($response);

		return $response_body;
	}

	/**
	 * Process the response and decode it.
	 *
	 * @param   JHttpResponse  $response      The response.
	 * @param   integer        $expectedCode  The expected "good" code.
	 *
	 * @throws DomainException
	 *
	 * @return mixed
	 */
	public function processResponse(JHttpResponse $response, $expectedCode = 200)
	{
		// Validate the response code.
		if ($response->code != $expectedCode)
		{
			// Decode the error response and throw an exception.
			$error = new SimpleXMLElement($response->body);
			throw new DomainException($error->message, $response->code);
		}

		return new SimpleXMLElement($response->body);
	}

	/**
	 * Creates the Authorization request header (which handles authentication).
	 *
	 * @param   string  $httpVerb  The HTTP Verb (GET, PUT, etc)
	 * @param   string  $url       The target url of the request
	 * @param   string  $headers   The headers of the request
	 *
	 * @return string The Authorization request header
	 *
	 * @since   ??.?
	 */
	public function createAuthorization($httpVerb, $url, $headers)
	{
		$authorization = "AWS " . $this->options->get('api.accessKeyId') . ":";

		$contentMD5 = "";
		$contentType = "";
		$date = "";

		if (array_key_exists("Content-MD5", $headers))
		{
			$contentMD5 = $headers["Content-MD5"];
		}

		if (array_key_exists("Content-type", $headers))
		{
			$contentType = $headers["Content-type"];
		}

		if (array_key_exists("Date", $headers))
		{
			$date = $headers["Date"];
		}

		$stringToSign = $httpVerb . "\n"
			. $contentMD5 . "\n"
			. $contentType . "\n"
			. $date . "\n"
			. $this->createCanonicalizedAmzHeaders($headers)
			. $this->createCanonicalizedResource($url);

		// Signature = Base64( HMAC-SHA1( YourSecretAccessKeyID, UTF-8-Encoding-Of( StringToSign ) ) );
		$signature = base64_encode(
			hash_hmac("sha1", utf8_encode($stringToSign), $this->options->get('api.secretAccessKey'), true)
		);

		$authorization .= $signature;

		return $authorization;
	}

	/**
	 * Creates the canonicalized amz headers used for calculating the signature
	 * in the Authorization header.
	 *
	 * @param   string  $headers  The headers of the request
	 *
	 * @return	string	The canonicalized amz headers
	 *
	 * @since   ??.?
	 */
	public function createCanonicalizedAmzHeaders($headers)
	{
		$xAmzHeaders = array();

		foreach (array_keys($headers) as $header_key)
		{
			// Convert each HTTP header name to lowercase. For example, 'X-Amz-Date' becomes 'x-amz-date'.
			$lowercaseHeader = strtolower($header_key);

			// Select all HTTP request headers that start with 'x-amz-' (using a case-insensitive comparison)
			if (strpos($lowercaseHeader, 'x-amz-') === 0)
			{
				/**
				 * Combine header fields with the same name into one "header-name:comma-separated-value-list"
				 *  pair as prescribed by RFC 2616, section 4.2, without any whitespace between values.
				 * For example, the two metadata headers 'x-amz-meta-username: fred' and
				 *  'x-amz-meta-username: barney' would be combined into the single header
				 *  'x-amz-meta-username: fred,barney'.
				 */
				if (is_array($headers[$header_key]))
				{
					$commaSeparatedValues = implode(',', $headers[$header_key]);
					$xAmzHeaders[$lowercaseHeader] = $commaSeparatedValues;
				}
				else
				{
					$xAmzHeaders[$lowercaseHeader] = $headers[$header_key];
				}
			}
		}

		// Sort the collection of headers lexicographically by header name.
		ksort($xAmzHeaders);

		// Convert the array to a string
		$xAmzHeadersString = "";

		foreach (array_keys($xAmzHeaders) as $headerKey)
		{
			$xAmzHeadersString .= $headerKey . ":" . $xAmzHeaders[$headerKey] . "\n";
		}

		return $xAmzHeadersString;
	}

	/**
	 * Creates the canonicalized resource used for calculating the signature
	 * in the Authorization header.
	 *
	 * @param   string  $url  The target url of the request
	 *
	 * @return	string	The canonicalized resource
	 *
	 * @since   ??.?
	 */
	public function createCanonicalizedResource($url)
	{
		// Gets the host by parsing the url
		$parsedURL = parse_url($url);
		$host = $parsedURL["host"];

		// Gets the bucket from the host
		if (strcmp($host, $this->options->get('api.url')) == 0)
		{
			$bucket = "";
		}
		else
		{
			$bucket = substr($host, 0, strpos($host, $this->options->get('api.url')) - 1);
		}

		// For a request that does not address a bucket, such as GET Service, append "/".
		$canonicalizedResource = "/";

		if ($bucket !== "")
		{
			/**
			 * For a virtual hosted-style request "https://johnsmith.s3.amazonaws.com/photos/puppy.jpg",
			 *  the CanonicalizedResource is "/johnsmith/photos/puppy.jpg".
			 */
			$canonicalizedResource .= $bucket;

			if (array_key_exists("path", $parsedURL))
			{
				$canonicalizedResource .= $parsedURL["path"];
			}

			/**
			 * If the request addresses a subresource, such as ?versioning, ?location, ?acl, ?torrent, ?lifecycle
			 *  or ?versionid, append the subresource, its value if it has one, and the question mark.
			 * Subresources must be lexicographically sorted by subresource name and separated by '&'
			 */
			if (array_key_exists("query", $parsedURL))
			{
				$queryParameters = explode("&", $parsedURL["query"]);
				asort($queryParameters);
				$sortedQueryParameters = implode("&", $queryParameters);
				$canonicalizedResource .= "?" . $sortedQueryParameters;
			}
		}

		return $canonicalizedResource;
	}
}
