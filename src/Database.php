<?php

namespace App;

class Database
{
    public $conn;

    private $config;

    public function __construct()
    {
        $this->config = include 'config.php';

        $this->conn = new \PDO('mysql:host=' . $this->config['host'] . ';dbname=' . $this->config['name'], $this->config['user'], $this->config['pass']);
    }

    public function saveMove($move)
    {
        $query = "INSERT INTO moves (player_id, square_id) VALUES (:player, :square)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':player', $move['player']);
        $stmt->bindParam(':square', $move['move']);

        $stmt->execute();

        return (int) $this->conn->lastInsertId();
    }

    public function getMoves()
    {
        $query = "SELECT * FROM moves ORDER BY id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getMovesTotal()
    {
        $query = "SELECT * FROM moves";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function getLastPlayer()
    {
        $query = "SELECT player_id FROM moves ORDER BY id DESC LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchColumn(0);
    }

    public function resetGame()
    {
        $query = "TRUNCATE moves";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }
}
