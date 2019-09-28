<?php

namespace App\Controller;

use Component\Http\Request;
use Component\Http\JsonResponse;
use Component\Routing\Router;

class IndexAction {

  public function __invoke(Request $request) {
    return [
      'version' => '1.0'
    ];
  }
}
