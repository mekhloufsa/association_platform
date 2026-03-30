<?php

class Volunteer extends Model {
    protected $table = 'volunteers';

    public function findByUserId($userId) {
        $stmt = $this->db->prepare("SELECT v.*, c.title as campaign_title, c.start_date, a.name as association_name 
                                  FROM {$this->table} v 
                                  JOIN campaigns c ON v.campaign_id = c.id 
                                  JOIN associations a ON c.association_id = a.id 
                                  WHERE v.user_id = ? 
                                  ORDER BY v.registered_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function register($userId, $campaignId) {
        // Check if already registered
        $stmt = $this->db->prepare("SELECT id FROM {$this->table} WHERE user_id = ? AND campaign_id = ?");
        $stmt->execute([$userId, $campaignId]);
        if ($stmt->fetch()) return true; // Already registered

        $sql = "INSERT INTO {$this->table} (user_id, campaign_id, status) VALUES (?, ?, 'registered')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId, $campaignId]);
    }

    public function findByCampaignId($campaignId) {
        $stmt = $this->db->prepare("SELECT v.*, u.nom, u.prenom, u.email, u.phone 
                                  FROM {$this->table} v 
                                  JOIN users u ON v.user_id = u.id 
                                  WHERE v.campaign_id = ? 
                                  ORDER BY v.registered_at DESC");
        $stmt->execute([$campaignId]);
        return $stmt->fetchAll();
    }

    public function updateStatus($id, $status) {
        $sql = "UPDATE {$this->table} SET status = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $id]);
    }

    public function isRegistered($userId, $campaignId) {
        $stmt = $this->db->prepare("SELECT id FROM {$this->table} WHERE user_id = ? AND campaign_id = ? AND status != 'cancelled'");
        $stmt->execute([$userId, $campaignId]);
        return (bool)$stmt->fetch();
    }
}
