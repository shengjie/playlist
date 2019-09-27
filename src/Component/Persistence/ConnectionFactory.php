<?php

namespace Component\Persistence;

/**
 * This class responsible for construct and keep a new PDO connection
 */
class ConnectionFactory {

  /**
   * @var \PDO
   */
  private $pdo;

  /**
   * @var string
   */
  private $username;

  /**
   * @var string
   */
  private $password;

  /**
   * @var string
   */
  private $dsn;

  public function __construct(array $config)
  {
    $this->username = $config['username'];
    $this->password = $config['password'];
    $this->dsn = $config['dsn'];
  }

  public function getConnection(): \PDO {
    if (null === $this->pdo) {
      $this->pdo = new \PDO($this->dsn, $this->username, $this->password);
      $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    return $this->pdo;
  }
}
