<?php

namespace Component\Http;

class Request {
  /**
   * @var string
   */
  public $method;

  /**
   * @var string
   */
  public $pathInfo;

  /**
   * @var array
   */
  public $post;

  /**
   * @var array
   */
  public $query;

  /**
   * @var array
   */
  public $attributes;
}
