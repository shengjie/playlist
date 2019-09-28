<?php

namespace Component\Routing;

class Route {
  /**
  * @var string
  */
  public $name;

  /**
   * @var string the path of matching, example "/abc/123/{id}"
   */
  public $path;

  /**
  * @var string http method in upper case, example: "GET", "POST"
  */
  public $method;

  /**
   * @var string class to handle this action
   */
  public $action;
}
