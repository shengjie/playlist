<?php

namespace Component\Http;

class JsonResponseFactory {
  public function createFromActionResult(Request $request, $actionResult): Response {
    return new Response(json_encode($actionResult), 200, [
      'Content-Type' => 'application/json'
    ]);
  }

  public function createFromException(Request $request, \Exception $exception): Response {
    $code = 500;
    $message = 'internal server error';
    if ($exception instanceof Exceptions\HttpException) {
      $message = $exception->getMessage();
      $code = $exception->getCode();
    }

    return new Response(\json_encode(['error' => $message]), $code, [
      'Content-Type' => 'application/json'
    ]);
  }
}
