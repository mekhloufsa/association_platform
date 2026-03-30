<?php

class Annonce extends Model {
    protected $table = 'annonces';

    public function findPublished($limit = 10) {
        $stmt = $this->db->prepare("SELECT a.*, u.prenom, u.nom 
                                  FROM {$this->table} a 
                                  JOIN users u ON a.author_user_id = u.id 
                                  WHERE a.status = 'published' 
                                  ORDER BY a.published_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT a.*, u.prenom, u.nom 
                                  FROM {$this->table} a 
                                  JOIN users u ON a.author_user_id = u.id 
                                  WHERE a.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findVisible($isLoggedIn) {
        $sql = "SELECT a.*, u.prenom, u.nom 
                FROM {$this->table} a 
                JOIN users u ON a.author_user_id = u.id 
                WHERE a.status = 'published'";
        
        if (!$isLoggedIn) {
            $sql .= " AND a.visibility = 'public'";
        }
        
        $sql .= " ORDER BY a.published_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findAll() {
        $stmt = $this->db->prepare("SELECT a.*, u.prenom, u.nom 
                                  FROM {$this->table} a 
                                  JOIN users u ON a.author_user_id = u.id 
                                  ORDER BY a.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (title, content, author_user_id, status, image_path, attachment_path, visibility, published_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['content'],
            $data['author_user_id'],
            $data['status'],
            $data['image_path'] ?? null,
            $data['attachment_path'] ?? null,
            $data['visibility'] ?? 'public',
            $data['status'] === 'published' ? date('Y-m-d H:i:s') : null
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                title = ?, 
                content = ?, 
                status = ?, 
                visibility = ?";
        
        $params = [
            $data['title'],
            $data['content'],
            $data['status'],
            $data['visibility']
        ];

        if (array_key_exists('image_path', $data) && $data['image_path'] !== false) {
            $sql .= ", image_path = ?";
            $params[] = $data['image_path'];
        }

        if (array_key_exists('attachment_path', $data) && $data['attachment_path'] !== false) {
            $sql .= ", attachment_path = ?";
            $params[] = $data['attachment_path'];
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}
