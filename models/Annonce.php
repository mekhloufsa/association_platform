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
}
