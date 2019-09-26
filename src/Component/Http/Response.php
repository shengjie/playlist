<?php

namespace Component\Http;

class Response {

  /**
   * @var string
   */
  private $content;

  /**
   * @var int
   */
  private $statusCode;

  /**
   * @var array
   */
  private $headers;

  public function __construct(string $content, int $statusCode = 200, array $headers = []) {
    $this->content = $content;
    $this->statusCode = $statusCode;
    $this->headers = $headers;
  }


  public function send() {
    \http_response_code($this->statusCode);
    foreach($this->headers as $headerK => $headerV) {
      \header($headerK.': '.$headerV);
    }
    echo $this->content;
  }
}
