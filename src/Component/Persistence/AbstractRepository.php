<?php

namespace Component\Persistence;

abstract class AbstractRepository {
  public function __construct(ConnectionFactory $connectionFactory) {
    $this->connectionFactory = $connectionFactory;
  }
}
