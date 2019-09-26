<?php

namespace App;

class Kernel {
  public function handle(Request $request) {
    $response = ResponseFactory::createFromRequest($request);
    return $response;
  }
}
