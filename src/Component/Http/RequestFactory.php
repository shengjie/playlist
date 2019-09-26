<?php

namespace Component\Http;

class RequestFactory {
  public static function createFromGlobals() {
    $request = new Request();
    $request->method = $_SERVER['REQUEST_METHOD'];
    $request->post = $_POST;
    $request->query = $_GET;
    $request->pathInfo = $_SERVER['PATH_INFO'];
    return $request;
  }
}
