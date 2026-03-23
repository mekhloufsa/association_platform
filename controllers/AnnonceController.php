<?php

class AnnonceController extends Controller {
    public function index() {
        $model = new Annonce();
        $annonces = $model->findPublished();
        
        $this->render('annonces/index', [
            'annonces' => $annonces
        ]);
    }
}
