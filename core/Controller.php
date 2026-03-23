<?php

class Controller {
    /**
     * Rendu d'une vue
     */
    protected function render($view, $data = [], $layout = 'main') {
        // Extraction des données variables pour les rendre accessibles dans la vue
        extract($data);
        
        ob_start();
        $viewFile = '../views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            die("La vue $view n'existe pas.");
        }
        
        $content = ob_get_clean();
        
        // Inclusion du layout
        $layoutFile = '../views/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content; // Affichage brut si pas de layout
        }
    }

    /**
     * Redirection
     */
    protected function redirect($url) {
        $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
        header('Location: ' . $basePath . $url);
        exit;
    }

    /**
     * Set a flash message
     */
    protected function setFlash($type, $message) {
        if (!isset($_SESSION)) session_start();
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }
}
