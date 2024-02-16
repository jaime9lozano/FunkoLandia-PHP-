<?php

namespace services;

use models\Categoria;
use Ramsey\Uuid\Uuid;
use PDO;

require_once __DIR__ . '/../models/Categoria.php';

class CategoriasService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categorias ORDER BY id ASC");
        $stmt->execute();

        $categorias = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categoria = new Categoria(
                $row['id'],
                $row['nombre'],
                $row['created_at'],
                $row['updated_at'],
                $row['is_deleted']
            );
            $categorias[] = $categoria;
        }
        return $categorias;
    }

    public function findByName($name)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categorias WHERE nombre = :nombre");
        $stmt->execute(['nombre' => $name]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return false;
        }
        $categoria = new Categoria(
            $row['id'],
            $row['nombre'],
            $row['created_at'],
            $row['updated_at'],
            $row['is_deleted']
        );
        return $categoria;
    }
    public function deleteById($id)
    {
        $sql = "UPDATE categorias SET
            is_deleted = true
            WHERE id = :id"; // Consulta SQL para eliminar

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR); // Vincular el ID como un entero

        return $stmt->execute(); // Ejecutar la consulta y devolver el resultado
    }

    public function update(Categoria $categoria)
    {
        $sql = "UPDATE categorias SET
            nombre = :nombre,
            updated_at = :updated_at
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':nombre', $categoria->nombre, PDO::PARAM_STR);
        $categoria->updatedAt = date('Y-m-d H:i:s');
        $stmt->bindValue(':updated_at', $categoria->updatedAt, PDO::PARAM_STR);
        $stmt->bindValue(':id', $categoria->id, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function save(Categoria $categoria)
    {
        $uuid = Uuid::uuid4()->toString();;

        $sql = "INSERT INTO categorias (id, nombre, created_at, updated_at)
            VALUES (:id, :nombre, :created_at, :updated_at)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':id', $uuid, PDO::PARAM_STR);
        $stmt->bindValue(':nombre', $categoria->nombre, PDO::PARAM_STR);

        $categoria->createdAt = date('Y-m-d H:i:s');
        $stmt->bindValue(':created_at', $categoria->createdAt, PDO::PARAM_STR);

        $categoria->updatedAt = date('Y-m-d H:i:s');
        $stmt->bindValue(':updated_at', $categoria->updatedAt, PDO::PARAM_STR);

        return $stmt->execute();
    }
    public function findAllWithName($searchTerm = null)
    {
        $sql = "SELECT c.*, null AS categoria_nombre
        FROM categorias c
        WHERE c.is_deleted = false";

        if ($searchTerm) {
            $searchTerm = '%' . strtolower($searchTerm) . '%';
            $sql .= " WHERE LOWER(c.nombre) LIKE :searchTerm";
        }

        $sql .= " ORDER BY c.id ASC";

        $stmt = $this->pdo->prepare($sql);

        if ($searchTerm) {
            $stmt->bindValue(':searchTerm', $searchTerm, PDO::PARAM_STR);
        }

        $stmt->execute();

        $categorias = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categoria = new Categoria(
                $row['id'],
                $row['nombre'],
                $row['created_at'],
                $row['updated_at'],
                $row['is_deleted']
            );
            $categorias[] = $categoria;
        }
        return $categorias;
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM categorias WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        $categoria = new Categoria(
                $row['id'],
                $row['nombre'],
                $row['created_at'],
                $row['updated_at'],
                $row['is_deleted']
        );
        return $categoria;
    }

    public function findAllWithDeleted($searchTerm = null)
    {
        $sql = "SELECT c.*, null AS categoria_nombre
        FROM categorias c
        WHERE c.is_deleted = true";

        if ($searchTerm) {
            $searchTerm = '%' . strtolower($searchTerm) . '%';
            $sql .= " WHERE LOWER(c.nombre) LIKE :searchTerm";
        }

        $sql .= " ORDER BY c.id ASC";

        $stmt = $this->pdo->prepare($sql);

        if ($searchTerm) {
            $stmt->bindValue(':searchTerm', $searchTerm, PDO::PARAM_STR);
        }

        $stmt->execute();

        $categorias = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categoria = new Categoria(
                $row['id'],
                $row['nombre'],
                $row['created_at'],
                $row['updated_at'],
                $row['is_deleted']
            );
            $categorias[] = $categoria;
        }
        return $categorias;
    }
}