<?php

/**
 * Jenny - a micro PHP 5 framework.
 * 
 * @package Jenny\Route
 * @author Jenny Tang
 * @copyright (c) 2013,Jenny Tang 
 * @since 0.0.1
 */

namespace Jenny\Route;

class Route {

	/**
	 * @var string The route patten(e.g. '/articles/:page')
	 */
	protected $pattern;
	/**
	 * @var callable The callable function combined to the route
	 */
	protected $callback;
	/**
	 * @var string The controller binded to the route
	 */
	protected $controller;
	/**
	 * @var array The HTTP methods suppoted by this route
	 */
	protected $methods = array();
	/**
	 * @var array The paramNames of this route(e.g.'page' of the route pattern '/articles/:page') 
	 */
	protected $paramNames = array();
	/**
	 * @var array The path of this route(e.g.'article' of the route pattern '/articles/:page') 
	 */
	protected $path = array();
	/**
	 * If the given uri matches the pattern, set the param key-value array
	 * @var array The key-value array contains the value of the pamasNames
	 */
	protected $params = array();
	/**
	 * @var number Quantity of the paramNames
	 */
	protected $paramNum = 0;
	/**
	 * The construct function of class Route
	 * @var string $pattern The url pattern of this route
	 * @var callable $callable The callback function to this route
	 */

	public function __construct($pattern, $callable) {
		$this->setPattern($pattern);
		$this->setCallable($callable);
		$this->setPatternItems();
	}
	/**
	 * Set the route pattern
	 * @var string $pattern
	 */
	public function setPattern($pattern) {
		$this->pattern = $pattern;
	}
	/**
	 * Set the callable to the route
	 * @var callable
	 */
	public function setCallable($callable) {
		if (!is_callable($callable)) {
			throw new \InvalidArgumentException('Route callable must be callable');
		}
		$this->callable = $callable;
	}
	/**
	 * Get the route pattern
	 * @return string $pattern
	 */
	public function getPattern() {
		return $this->pattern;
	}
	/**
	 * get the route callback function
	 * @return callable $callable
	 */
	public function getCallable() {
		return $this->callable;
	}
	/**
	 * Set the key-value array of paramNames and the value by
	 * combine the paramNames array and the params
	 * @var array
	 */
	public function setParams($param) {
		$this->params = array_combine($this->paramNames, $param);
		print_r($this->params);
	}
	/**
	 * Get the key-value array of the params
	 * @return array
	 */
	public function getParams(){
		return $this->params;
	}
	/**
	 * Get the paramNames of the route from the pattern
	 */
	private function setPatternItems() {
		$pattern = $this->pattern;

		$this->paramNum = preg_match_all("#:(\w+)#", $pattern, $matches);
		foreach ($matches[1] as $name) {
			$this->paramNames[] = $name;
		}

		preg_match_all("#(/\w+)#", $pattern, $matches);
		foreach ($matches[1] as $path) {
			$this->path[] = ltrim($path, '/');
		}

		$this->preg = preg_replace("#:(\w+)#", "([^/]+)", $pattern);
		$this->preg = "#" . $this->preg . "#";
		echo $this->preg;
	}

	/**
	 * Tells if the given url string match the route pattern
	 * @var $route string the route
	 * @return bool
	 */
	public function match($uri) {
		$result = preg_match($this->preg, $uri, $matches);

		if ($result != 0) {
			array_shift($matches);
			$this->setParams($matches);
		} else {
			return false;
		}
	}

}
