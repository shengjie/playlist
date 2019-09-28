<?php

namespace Component\Http;

/**
 * This class holds all request information
 */
class Request {
  /**
   * @var string http method in upper case
   */
  public $method;

  /**
   * @var string
   */
  public $requestUri;

  /**
   * @var string
   */
  public $pathInfo;

  /**
   * @var array holds posted data when http method is POST/PUT
   */
  public $post;

  /**
   * @var array holds querystring data
   */
  public $query;

  /**
   * @var array extra attributes, used to hold route path parameters like "{id}"
   */
  public $attributes;
}
