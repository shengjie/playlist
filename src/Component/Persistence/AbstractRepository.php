<?php

namespace Component\Persistence;

/**
 * Base repository class provide some database access helper
 */
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

  protected function exec(string $sql, array $argments = []): \PDOStatement {
    $stmt = $this->getConnection()->prepare($sql);
    foreach($argments as $argK => $argV) {
      $stmt->bindValue($argK, $argV);
    }
    $stmt->execute();
    return $stmt;
  }
}
