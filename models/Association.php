<?php

class Association extends Model {
    protected $table = 'associations';

    public function findApproved($search = '') {
        $sql = "SELECT a.*, u.nom as president_nom, u.prenom as president_prenom 
                FROM {$this->table} a 
                JOIN users u ON a.president_user_id = u.id 
                WHERE a.national_account_status = 'approved'";
        $params = [];

        if ($search) {
            $sql .= " AND (a.name LIKE ? OR a.description LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $sql .= " ORDER BY a.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findBySlug($slug) {
        $stmt = $this->db->prepare("SELECT a.*, u.nom as president_nom, u.prenom as president_prenom 
                                  FROM {$this->table} a 
                                  JOIN users u ON a.president_user_id = u.id 
                                  WHERE a.slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public function findByPresidentId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE president_user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function findPending() {
        $stmt = $this->db->prepare("SELECT a.*, u.nom as president_nom, u.prenom as president_prenom 
                                  FROM {$this->table} a 
                                  JOIN users u ON a.president_user_id = u.id 
                                  WHERE a.national_account_status = 'pending' 
                                  ORDER BY a.created_at ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findAll($search = '', $status = '') {
        $sql = "SELECT a.*, u.nom as president_nom, u.prenom as president_prenom 
                FROM {$this->table} a 
                JOIN users u ON a.president_user_id = u.id WHERE 1=1";
        $params = [];

        if ($search) {
            $sql .= " AND (a.name LIKE ? OR a.description LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if ($status) {
            $sql .= " AND a.national_account_status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY a.name ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT a.*, u.nom as president_nom, u.prenom as president_prenom, u.email as president_email 
                                  FROM {$this->table} a 
                                  JOIN users u ON a.president_user_id = u.id 
                                  WHERE a.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateThankYouMessage($id, $message) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET thank_you_message = ? WHERE id = ?");
        return $stmt->execute([$message, $id]);
    }

    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET national_account_status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}
