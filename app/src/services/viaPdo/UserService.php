<?php

namespace youcloud\Services\ViaPdo;
require_once 'Bdd.php';

class UserService {
    protected $bdd;

    public function __construct(Bdd $bdd) {
        $this->bdd = $bdd;
    }

    public function verifyUser($username, $password) {
        
        $pdo = $this->bdd->connect;
        $stmt = $pdo->prepare('SELECT * FROM users WHERE name = ?');
        //bind ? on username
        $stmt->execute([$username]);    

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    public function registerUser($username, $password) {

        if ($this->usernameExists($username)) {
            return false;
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $pdo = $this->bdd->connect;
        $stmt = $pdo->prepare('INSERT INTO users (name, password) VALUES (?, ?)');
        //bind username and password to the prepared statement
        $stmt->execute([$username, $hashed_password]);
        
        $success = $stmt->rowCount() > 0;
    
        return $success;
    }

    public function usernameExists($username) {
        $pdo = $this->bdd->connect;
        $stmt = $pdo->prepare('SELECT * FROM users WHERE name = ?');
        $stmt->execute([$username]);
    
        $user = $stmt->fetch();
    
        return $user !== false;
    }
    public function getUserId($username){
        $pdo = $this->bdd->connect;
        $stmt = $pdo->prepare('SELECT id FROM users WHERE name = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        return $user['id'];
    }

    public function getUser($userId){
        $pdo = $this->bdd->connect;
        $stmt = $pdo->prepare('SELECT name FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        return $user;
    }

}
