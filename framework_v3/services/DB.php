<?php
namespace App\services;

use App\traits\TSingleton;
use PDO;

class DB
{
  use TSingleton;

  protected $connect;
  protected $config = [
    'driver' => 'mysql',
    'host' => 'localhost',
    'dbname' => 'gbphp',
    'charset' => 'UTF8',
    'port' => 3305,
    'user' => 'root',
    'password' => 'root',
  ];

  protected function getConnect()
  {
    if (empty($this->connect)) {
      $this->connect = new PDO(
        $this->getPrepareDsnString(),
        $this->config['user'],
        $this->config['password']
      );
      $this->connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    return $this->connect;
  }

  private function getPrepareDsnString()
  {
    return sprintf("%s:host=%s;dbname=%s;charset=%s;port=%s",
      $this->config['driver'],
      $this->config['host'],
      $this->config['dbname'],
      $this->config['charset'],
      $this->config['port']
    );
  }

  /**
   * @param $sql
   * @param array $params
   * @return bool|\PDOStatement
   */
  protected function query($sql, $params = [])
  {
    $PDOStatement = $this->getConnect()->prepare($sql);
    $PDOStatement->execute($params);
    return $PDOStatement;
  }

  public function find($sql, $params = [])
  {
    return $this->query($sql, $params)->fetch();
  }

  public function findAll($sql, $params = [])
  {
    return $this->query($sql, $params)->fetchAll();
  }

  /**
   * Запрос, не возвращающий ответа
   * @param $sql
   * @param array $params
   * @return bool|\PDOStatement
   */
  public function execute($sql, $params = [])
  {
    return$this->query($sql);
  }
}