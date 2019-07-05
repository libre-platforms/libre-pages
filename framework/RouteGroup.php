<?php
  declare(strict_types=1);
  namespace Framework;

  class RouteGroup {
    protected $_route;
    protected $_group_builder;

    function __construct(string $route, \Closure $group_builder) {
      $this->_route = $route;
      $this->_group_builder = $group_builder;
    }

    function get_router() {
      $router = new Router($this->_route);
      $builder = $this->_group_builder;
      $builder($router);
      return $router;
    }
  }