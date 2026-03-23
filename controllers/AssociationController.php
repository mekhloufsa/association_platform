<?php

class AssociationController extends Controller {
    public function index() {
        $model = new Association();
        $associations = $model->findApproved();
        
        $wilayaModel = new Wilaya();
        $wilayas = $wilayaModel->findAll();
        
        $this->render('associations/index', [
            'associations' => $associations,
            'wilayas' => $wilayas
        ]);
    }

    public function show($slug) {
        $model = new Association();
        $association = $model->findBySlug($slug);
        
        if (!$association) {
            http_response_code(404);
            die("Association non trouvée.");
        }

        $siegeModel = new Siege();
        $sieges = $siegeModel->findAllPubliclyVisible($association['id']);

        $campaignModel = new Campaign();
        $campaigns = $campaignModel->findByAssociationId($association['id']);
        
        // Only show open campaigns publicly
        $openCampaigns = array_filter($campaigns, function($c) {
            return $c['status'] === 'open' && strtotime($c['end_date']) >= strtotime(date('Y-m-d'));
        });

        $this->render('associations/show', [
            'association' => $association,
            'sieges' => $sieges,
            'campaigns' => $openCampaigns
        ]);
    }
}
