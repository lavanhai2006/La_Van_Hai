<?php
/**
 * app/Models/BaseModel.php
 * Base class cho tất cả Model
 */

declare(strict_types=1);

namespace App\Models;

use PDO;

abstract class BaseModel
{
    protected PDO $db;
    protected string $table = '';
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Tìm bản ghi theo ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ? LIMIT 1"
        );
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Lấy tất cả bản ghi
     */
    public function findAll(string $orderBy = ''): array
    {
        $sql = "SELECT * FROM `{$this->table}`";
        if ($orderBy) {
            $sql .= " ORDER BY $orderBy";
        }
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Xoá bản ghi theo ID
     */
    public function deleteById(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?"
        );
        return $stmt->execute([$id]);
    }

    /**
     * Đếm tổng bản ghi
     */
    public function count(string $where = '', array $params = []): int
    {
        $sql = "SELECT COUNT(*) FROM `{$this->table}`";
        if ($where) $sql .= " WHERE $where";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Phân trang
     */
    public function paginate(int $page = 1, int $perPage = 20, string $where = '', array $params = [], string $orderBy = 'id DESC'): array
    {
        $offset = ($page - 1) * $perPage;
        $total  = $this->count($where, $params);

        $sql = "SELECT * FROM `{$this->table}`";
        if ($where) $sql .= " WHERE $where";
        $sql .= " ORDER BY $orderBy LIMIT $perPage OFFSET $offset";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return [
            'data'         => $stmt->fetchAll(),
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'last_page'    => (int)ceil($total / $perPage),
        ];
    }
}
