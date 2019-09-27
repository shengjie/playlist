<?php

namespace Component\Persistence;

abstract class AbstractRepository {
  public function __construct(ConnectionFactory $connectionFactory) {
    $this->connectionFactory = $connectionFactory;
  }

  protected function getConnection() {
    return $this->connectionFactory->getConnection();
  }

  protected function prepare(string $sql) {
    return $this->getConnection()->prepare($sql);
  }
}
