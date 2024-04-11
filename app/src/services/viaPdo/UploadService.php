<?php

namespace youcloud\Services\ViaPdo;
require_once 'Bdd.php';

class UploadService {
    protected $bdd;

    public function __construct(Bdd $bdd) {
        $this->bdd = $bdd;
    }

    public function addFile($userId, $tile, $description, $location, $type, $originalName ) {
        
        $pdo = $this->bdd->connect;
        $stmt = $pdo->prepare('INSERT INTO files (user_id, title, description, location, type,original_name) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$userId, $tile, $description, $location, $type, $originalName]);

        return;
    }

    public function getFiles($userId) {
        $pdo = $this->bdd->connect;
        $stmt = $pdo->prepare('SELECT * FROM files WHERE user_id = ?');
        $stmt->execute([$userId]);
        $files = $stmt->fetchAll();
        return $files;
    }

    public function getFile($fileId) {
        $pdo = $this->bdd->connect;
        $stmt = $pdo->prepare('SELECT * FROM files WHERE id = ?');
        $stmt->execute([$fileId]);
        $file = $stmt->fetch();

        $filePath = dirname(__DIR__, 3) . '/storage/' . $file['location'];

        // Vérifiez si le fichier existe
        if (!file_exists($filePath)) {
            throw new \Exception('File not found');
        }
        $fileContent = file_get_contents($filePath);

        return $fileContent;
    }

    public function getFileInfo($fileId) {
        $pdo = $this->bdd->connect;
        $stmt = $pdo->prepare('SELECT * FROM files WHERE id = ?');
        $stmt->execute([$fileId]);
        $file = $stmt->fetch();
        return $file;
    }

    public function fileMatchUser($userId, $fileId) {
        $pdo = $this->bdd->connect;
        $stmt1 = $pdo->prepare('SELECT * FROM files WHERE id = ?');
        $stmt1->execute([$fileId]);
        $file = $stmt1->fetch();
        if (!$file) {
            return false;
        }
        if ($file['user_id'] == $userId) {
            return true;
        } else {
            return 'denied';
        }

        
        // $stmt = $pdo->prepare('SELECT * FROM files WHERE user_id = ? AND id = ?');
        // $stmt->execute([$userId, $fileId]);
        // $file = $stmt->fetch();
        // //return file if it exists and error if not
        // return $file;

    }

    public function deleteFile($fileId) {
        $pdo = $this->bdd->connect;
        $stmt = $pdo->prepare('DELETE FROM files WHERE id = ?');
        $stmt->execute([$fileId]);
        return;
    }

}
