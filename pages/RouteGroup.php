<?php
/**
 * This file is part of LibrePages.
 *
 * LibrePages is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * LibrePages is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with LibrePages.  If not, see <https://www.gnu.org/licenses/>.
 * 
 * @author    Jörn Neumeyer <contact@joern-neumeyer.de>
 * @copyright 2019 Jörn Neumeyer
*/

  declare(strict_types=1);
  namespace Pages;

  /**
   * Defindes a group routes which are grouped by a common route prefix.
   */
  class RouteGroup {
    /** @var string $_route The common prefix of the route group. */
    protected $_route;
    /** @var \Closure $_group_builder A closure, defining all the routes belonging to the group. */
    protected $_group_builder;

    /**
     * 
     * @param string $route The common prefix of the route group.
     * @param \Closure $group_builder A closure, defining all the routes belonging to the group.
     */
    function __construct(string $route, \Closure $group_builder) {
      $this->_route = $route;
      $this->_group_builder = $group_builder;
    }

    /**
     * Builds and returns a router for the route group with the specified prefix.
     * 
     * @return Router
     */
    function get_router() {
      $router = new Router($this->_route);
      ($this->_group_builder)($router);
      return $router;
    }
  }