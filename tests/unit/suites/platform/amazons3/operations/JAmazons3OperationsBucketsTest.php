<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Client
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_PLATFORM . '/joomla/amazons3/operations/service.php';

/**
 * Test class for JAmazons3.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Amazons3
 *
 * @since       ??.?
 */
class JAmazons3OperationsBucketsTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var    JRegistry  Options for the Amazons3 object.
	 * @since  ??.?
	 */
	protected $options;

	/**
	 * @var    JAmazons3  Object under test.
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
		$this->object = new JAmazons3($this->options);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 *
	 * @return void
	 */
	protected function tearDown()
	{
	}

	/**
	 * Tests the magic __get method - get
	 *
	 * @since  ??.?
	 *
	 * @return void
	 */
	public function test__GetGet()
	{
		$this->assertThat(
			$this->object->buckets->get,
			$this->isInstanceOf('JAmazons3OperationsBucketsGet')
		);
	}

	/**
	 * Tests the magic __get method - head
	 *
	 * @since  ??.?
	 *
	 * @return void
	 */
	public function test__GetHead()
	{
		$this->assertThat(
			$this->object->buckets->head,
			$this->isInstanceOf('JAmazons3OperationsBucketsHead')
		);
	}

	/**
	 * Tests the magic __get method - delete
	 *
	 * @since  ??.?
	 *
	 * @return void
	 */
	public function test__GetDelete()
	{
		$this->assertThat(
			$this->object->buckets->delete,
			$this->isInstanceOf('JAmazons3OperationsBucketsDelete')
		);
	}

	/**
	 * Tests the magic __get method - put
	 *
	 * @since  ??.?
	 *
	 * @return void
	 */
	public function test__GetPut()
	{
		$this->assertThat(
			$this->object->buckets->put,
			$this->isInstanceOf('JAmazons3OperationsBucketsPut')
		);
	}
}
