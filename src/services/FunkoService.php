<?php

namespace services;

use models\Funko;
use PDO;
use Ramsey\Uuid\Uuid;


require_once __DIR__ . '/../models/Funko.php';

class FunkoService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAllWithCategoryName($searchTerm = null)
    {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre
        FROM funkos p
        LEFT JOIN categorias c ON p.categoria_id = c.id";


        if ($searchTerm) {
            $searchTerm = '%' . strtolower($searchTerm) . '%'; // Convertir el término de búsqueda a minúsculas
            $sql .= " WHERE LOWER(p.nombre) LIKE :searchTerm";
        }

        $sql .= " ORDER BY p.id ASC";

        $stmt = $this->pdo->prepare($sql);

        if ($searchTerm) {
            // Vincula el mismo término de búsqueda a los dos parámetros de búsqueda
            $stmt->bindValue(':searchTerm', $searchTerm, PDO::PARAM_STR);
        }

        $stmt->execute();

        $funkos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $funko = new Funko(
                $row['id'],
                $row['nombre'],
                $row['imagen'],
                $row['precio'],
                $row['cantidad'],
                $row['created_at'],
                $row['updated_at'],
                $row['categoria_id'],
                $row['categoria_nombre'], // Pasamos el nombre de la categoría
                $row['is_deleted']
            );
            $funkos[] = $funko;
        }
        return $funkos;
    }

    public function findById($id)
    {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre
            FROM funkos p
            LEFT JOIN categorias c ON p.categoria_id = c.id
            WHERE p.id = :id"; // Filtrar por ID

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT); // Vincular el ID como un entero
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null; // Si no se encuentra el producto, devolver null
        }

        // Crear y devolver un objeto Producto con los datos obtenidos
        $funko = new Funko(
            $row['id'],
            $row['nombre'],
            $row['imagen'],
            $row['precio'],
            $row['cantidad'],
            $row['created_at'],
            $row['updated_at'],
            $row['categoria_id'],
            $row['categoria_nombre'], // Pasamos el nombre de la categoría
            $row['is_deleted']
        );

        return $funko;
    }

    public function deleteById($id)
    {
        $sql = "DELETE FROM funkos WHERE id = :id"; // Consulta SQL para eliminar

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT); // Vincular el ID como un entero

        return $stmt->execute(); // Ejecutar la consulta y devolver el resultado
    }

    public function update(Funko $funko)
    {
        $sql = "UPDATE funkos SET
            nombre = :nombre,
            imagen = :imagen,
            precio = :precio,
            cantidad = :cantidad,
            categoria_id = :categoria_id,
            updated_at = :updated_at
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':nombre', $funko->nombre, PDO::PARAM_STR);
        $stmt->bindValue(':imagen', $funko->imagen, PDO::PARAM_STR);
        $stmt->bindValue(':precio', $funko->precio, PDO::PARAM_STR);
        $stmt->bindValue(':cantidad', $funko->cantidad, PDO::PARAM_INT);
        $stmt->bindValue(':categoria_id', $funko->categoriaId, PDO::PARAM_INT);
        $funko->updatedAt = date('Y-m-d H:i:s');
        $stmt->bindValue(':updated_at', $funko->updatedAt, PDO::PARAM_STR);
        $stmt->bindValue(':id', $funko->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function save(Funko $funko)
    {
        $sql = "INSERT INTO funkos ( nombre, imagen, precio, cantidad, categoria_id, created_at, updated_at)
            VALUES (:nombre, :imagen, :precio, :cantidad, :categoria_id, :created_at, :updated_at)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':nombre', $funko->nombre, PDO::PARAM_STR);
        $funko->imagen = Funko::$IMAGEN_DEFAULT;
        $stmt->bindValue(':imagen', $funko->imagen, PDO::PARAM_STR);
        $stmt->bindValue(':precio', $funko->precio, PDO::PARAM_STR);
        $stmt->bindValue(':cantidad', $funko->cantidad, PDO::PARAM_INT);
        $stmt->bindValue(':categoria_id', $funko->categoriaId, PDO::PARAM_INT);
        $funko->createdAt = date('Y-m-d H:i:s');
        $stmt->bindValue(':created_at', $funko->createdAt, PDO::PARAM_STR);
        $funko->updatedAt = date('Y-m-d H:i:s');
        $stmt->bindValue(':updated_at', $funko->updatedAt, PDO::PARAM_STR);

        return $stmt->execute();
    }
}