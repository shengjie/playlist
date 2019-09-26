<?php

namespace App\Controller;

use Component\Http\Request;
use Component\Http\Response;

class IndexAction {

  /**
   * @var Router
   */
  private $router;

  public function __construct(Router $router) {
    $this->router = $router;
  }

  public function __invoke(Request $request) {
    return new Response(\json_encode([
      'links' => [
        $this->router->generate('')
      ]
    ]));
  }
}
