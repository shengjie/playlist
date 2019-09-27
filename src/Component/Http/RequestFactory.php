<?php

namespace Component\Http;

class RequestFactory {
  public static function createFromGlobals() {
    $request = new Request();
    $request->method = $_SERVER['REQUEST_METHOD'];
    $input = file_get_contents('php://input');
    if (!empty($input)) {
      $inputData = \json_decode($input, true);
      if (\json_last_error() === JSON_ERROR_NONE) {
        $request->post = $inputData;
      }
    } else {
      $request->post = $_POST;
    }
    $request->query = $_GET;
    $request->requestUri = $_SERVER['REQUEST_URI'];
    $request->pathInfo = explode('?', $request->requestUri)[0];
    return $request;
  }
}
