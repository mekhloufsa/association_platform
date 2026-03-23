<?php

class HomeController extends Controller {
    
    public function index() {
        // Dans le futur, on récupèrera les annonces/campagnes récentes ici
        $title = "Bienvenue sur Aura";
        $description = "Connectons les citoyens et les associations pour un impact positif.";

        $this->render('home/index', [
            'title' => $title,
            'description' => $description
        ]);
    }

}
