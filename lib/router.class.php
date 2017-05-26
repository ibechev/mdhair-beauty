<?php

class Router {

	protected $uri;
	protected $controller;
	protected $action;
	protected $params;
	protected $route;
	protected $method_prefix;
	protected $language;
	protected $layout;


	public function getUri() {
		return $this->uri;
	}

	public function getController() {
		return $this->controller;
	}

	public function getAction() {
		return $this->action;
	}

	public function getParams() {
		return $this->params;
	}

	public function getRoute() {
		return $this->route;
	}

	public function getLanguage() {
		return $this->language;
	}

	public function getMethodPrefix() {
		return $this->method_prefix;
	}

	public function getLayout() {
		return $this->layout;
	}

	public function __construct($uri) {
		
		$this->uri = urldecode(trim($uri, '/'));
		
		// Get defaults
		$routes = Config::get('routes');
		$this->route = Config::get('default_route');
		$this->method_prefix =  isset($routes[$this->route]) ? $routes[$this->route] : '';
		$this->language = Config::get('default_language');
		$this->controller = Config::get('default_controller');
		$this->action = Config::get('default_action');
		$this->layout = Config::get('default_layout');


		$uri_parts = explode('?', $this->uri);
	
		// Get path like /lng/controller/action/param1/param2/.../..
		$path = $uri_parts[0];

		$path_parts = explode('/', $path);

		if (count($path_parts)) {
			
			// Get route or language at first element of array (if any)
			if (in_array(strtolower(current($path_parts)), array_keys($routes))) {
				
				$this->route = strtolower(current($path_parts));
				$this->method_prefix = isset($routes[$this->route]) ? $routes[$this->route] : '';
				array_shift($path_parts);

			} elseif (in_array(strtolower(current($path_parts)), Config::get('language'))) {

				$this->language = strtolower(current($path_parts));
				array_shift($path_parts);
			}
			
			// Get controller - next element in the array (if any)
			if (current($path_parts)) {
				
				$this->controller = strtolower(current($path_parts));
				array_shift($path_parts);
			}

			// Get action (if any)
			if (current($path_parts)) {
				
				$this->action = strtolower(current($path_parts));
				array_shift($path_parts);
			}

			// Get parameters (if any)
			if (current($path_parts)) {
					
				$this->params = $path_parts;
			}

		}

	}

}