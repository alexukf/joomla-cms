<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Client
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_PLATFORM . '/joomla/amazons3/operations/objects.php';

/**
 * Test class for JAmazons3.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Amazons3
 *
 * @since       ??.?
 */
class JAmazons3OperationsObjectsGetTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var    JRegistry  Options for the Amazons3 object.
	 * @since  ??.?
	 */
	protected $options;

	/**
	 * @var    JAmazons3Object  Object under test.
	 * @since  ??.?
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 *
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->options = new JRegistry;
		$this->options->set('api.accessKeyId', 'testAccessKeyId');
		$this->options->set('api.secretAccessKey', 'testSecretAccessKey');
		$this->options->set('api.url', 's3.amazonaws.com');
		$this->options->set('testBucket', 'testBucket');
		$this->options->set('testObject', 'testObject');
		$this->options->set('versionId', '3/L4kqtJlcpXroDTDmpUMLUo');
		$this->options->set('range', '0-9');

		$this->client = $this->getMock('JAmazons3Http', array('delete', 'get', 'head', 'put', 'post', 'optionss3'));

		$this->object = new JAmazons3OperationsObjects($this->options, $this->client);
	}

	/**
	 * Common test operations for methods which use GET requests
	 *
	 * @param   string  $versionId  The version id
	 * @param   string  $range      The range of bytes to be returned
	 *
	 * @return  void
	 *
	 * @since   ??.?
	 */
	protected function commonGetTestOperations($versionId = null, $range = null)
	{
		$url = "https://" . $this->options->get("testBucket") . "." . $this->options->get("api.url")
			. "/" . $this->options->get("testObject");

		if ($versionId)
		{
			$url .= "?versionId=" . $versionId;
		}

		$headers = array(
			"Date" => date("D, d M Y H:i:s O"),
		);

		if (! is_null($range))
		{
			$headers['Range'] = "bytes=" . $range;
		}

		$authorization = $this->object->createAuthorization("GET", $url, $headers);
		$headers['Authorization'] = $authorization;

		$returnData = new JHttpResponse;
		$returnData->code = 200;
		$returnData->body = "<test>response</test>";
		$expectedResult = new SimpleXMLElement($returnData->body);

		$this->client->expects($this->once())
			->method('get')
			->with($url, $headers)
			->will($this->returnValue($returnData));

		return $expectedResult;
	}

	/**
	 * Tests the getBucket method
	 *
	 * @return  void
	 *
	 * @since   ??.?
	 */
	public function testGetObject()
	{
		$expectedResult = $this->commonGetTestOperations();
		$this->assertThat(
			$this->object->get->getObject(
				$this->options->get("testBucket"),
				$this->options->get("testObject")
			),
			$this->equalTo($expectedResult)
		);
	}

	/**
	 * Tests the getBucket method with a version Id
	 *
	 * @return  void
	 *
	 * @since   ??.?
	 */
	public function testGetObjectVersion()
	{
		$expectedResult = $this->commonGetTestOperations($this->options->get("versionId"));
		$this->assertThat(
			$this->object->get->getObject(
				$this->options->get("testBucket"),
				$this->options->get("testObject"),
				$this->options->get("versionId")
			),
			$this->equalTo($expectedResult)
		);
	}

	/**
	 * Tests the getBucket method with a version Id
	 *
	 * @return  void
	 *
	 * @since   ??.?
	 */
	public function testGetObjectRange()
	{
		$expectedResult = $this->commonGetTestOperations(null, $this->options->get("range"));
		$this->assertThat(
			$this->object->get->getObject(
				$this->options->get("testBucket"),
				$this->options->get("testObject"),
				null,
				$this->options->get("range")
			),
			$this->equalTo($expectedResult)
		);
	}
}
