<?php
/**
 * Part of the Joomla Framework OpenStreetMap Package
 *
 * @copyright  Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\OpenStreetMap;

use Joomla\Http\Http;
use Joomla\Registry\Registry;

/**
 * Joomla Framework class for interacting with the OpenStreetMap API.
 *
 * @since  1.0
 */
class OpenStreetMap
{
	/**
	 * Options for the OpenStreetMap object.
	 *
	 * @var    Registry
	 * @since  1.0
	 */
	protected $options;

	/**
	 * The HTTP client object to use in sending HTTP requests.
	 *
	 * @var    Http
	 * @since  1.0
	 */
	protected $client;

	/**
	 * The OAuth client.
	 *
	 * @var   OAuth
	 * @since 1.0
	 */
	protected $oauth;

	/**
	 * OpenStreetMap API object for changesets.
	 *
	 * @var    Changesets
	 * @since  1.0
	 */
	protected $changesets;

	/**
	 * OpenStreetMap API object for elements.
	 *
	 * @var    Elements
	 * @since  1.0
	 */
	protected $elements;

	/**
	 * OpenStreetMap API object for GPS.
	 *
	 * @var    GPS
	 * @since  1.0
	 */
	protected $gps;

	/**
	 * OpenStreetMap API object for info.
	 *
	 * @var    Info
	 * @since  1.0
	 */
	protected $info;

	/**
	 * OpenStreetMap API object for user.
	 *
	 * @var    User
	 * @since  1.0
	 */
	protected $user;

	/**
	 * Constructor.
	 *
	 * @param   OAuth     $oauth    OpenStreetMap OAuth client
	 * @param   Registry  $options  OpenStreetMap options object
	 * @param   Http      $client   The HTTP client object
	 *
	 * @since   1.0
	 */
	public function __construct(OAuth $oauth = null, Registry $options = null, Http $client = null)
	{
		$this->oauth = $oauth;
		$this->options = isset($options) ? $options : new Registry;
		$this->client  = isset($client) ? $client : new Http($this->options);

		// Setup the default API url if not already set.
		$this->options->def('api.url', 'http://api.openstreetmap.org/api/0.6/');
	}

	/**
	 * Method to get object instances
	 *
	 * @param   string  $name  Name of property to retrieve
	 *
	 * @return  OpenStreetMapObject  OpenStreetMap API object
	 *
	 * @since   1.0
	 * @throws  \InvalidArgumentException
	 */
	public function __get($name)
	{
		$class = __NAMESPACE__ . '\\' . ucfirst(strtolower($name));

		if (class_exists($class) && property_exists($this, $name))
		{
			if (false == isset($this->$name))
			{
				$this->$name = new $class($this->options, $this->client, $this->oauth);
			}

			return $this->$name;
		}

		throw new \InvalidArgumentException(sprintf('Argument %s produced an invalid class name: %s', $name, $class));
	}

	/**
	 * Get an option from the OpenStreetMap instance.
	 *
	 * @param   string  $key  The name of the option to get.
	 *
	 * @return  mixed  The option value.
	 *
	 * @since   1.0
	 */
	public function getOption($key)
	{
		return $this->options->get($key);
	}

	/**
	 * Set an option for the OpenStreetMap instance.
	 *
	 * @param   string  $key    The name of the option to set.
	 * @param   mixed   $value  The option value to set.
	 *
	 * @return  OpenStreetMap  This object for method chaining.
	 *
	 * @since   1.0
	 */
	public function setOption($key, $value)
	{
		$this->options->set($key, $value);

		return $this;
	}
}