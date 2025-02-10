<?php

// Vang Pao Jimmy 
// Eval PHP POO 
// DATE : 10/02/2025

require_once 'interface_form.php';

// Création de la classe FormGenerator
class FormGenerator implements FormGeneratorInterface {
    // Attribut
    private $action;
    private $method;
    private array $fields = [];
    private array $errors = [];

    // Constructeur
    public function __construct(string $action, string $method) {
        $this->action = $action;
        $this->method = $method;
    }
    // Fonction pour ajouter des champs
    public function addField(string $name, string $type, string $label, array $attributes = []): void {
        $this->fields[] = compact('name', 'type', 'label', 'attributes');
    }
// Fonction pour pour afficher le formulaire
    public function render(): void {
        $html = "<form action='{$this->action}' method='{$this->method}'>";
        foreach ($this->fields as $field) {
            $this->renderField($field);
        }
        $html .= "<button type='submit'>Submit</button>";
        $html .= "</form>";
        echo $html;
    }

    private function renderField(array $field): void {
        $attributes = $this->formAttribut($field['attributes']);
        $html = "<label for='{$field['name']}'>{$field['label']}</label><br>";
        
        if ($field['type'] === 'select') {
            $html .= "<select name='{$field['name']}' id='{$field['name']}' {$attributes}>";
            foreach ($field['attributes']['options'] as $option) {
                $html .= "<option value='{$option}'>{$option}</option>";
            }
            echo "</select>";
        } elseif ($field['type'] === 'textarea') {
            $html .= "<textarea name='{$field['name']}' id='{$field['name']}' {$attributes}></textarea><br>";
        } else {
            $html .= "<input type='{$field['type']}' name='{$field['name']}' id='{$field['name']}' {$attributes} /><br>";
        }
        
        if (isset($this->errors[$field['name']])) {
            $html .= "<span class='error'>{$this->errors[$field['name']]}</span>";
        }
        echo $html;
    }
    // Fonction bool
    private function formAttribut(array $attributes): string {
        $format = [];
        foreach ($attributes as $key => $value) {
            if ($key === 'required' && $value) 
            {
                $format[] = 'required';
            } 
            elseif ($key === 'disabled' && $value) 
            {
                $format[] = 'disabled';
            } 
            elseif ($key !== 'options') 
            {
                $format[] = "{$key}='{$value}'";
            }
        }
        return implode(' ', $format);
    }
    
    // Fonction qui traite les données soumises et valide les champs
    public function handleSubmission(): bool {
        if(isset($_POST['submit'])) {
        // Vérification des champs du formulaire
        foreach ($this->fields as $field) {
            $name = $field['name'];
            $value = $_POST[$name] ?? null;
            // Vérification du champ Nom obligatoire
            if (($field['attributes']['required'] ?? false) && empty($value)) {
                return $this->errors[$name] = "Le champ {$field['label']} est obligatoire.";
                // Vérification du champ Email 
            } elseif ($field['type'] === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                return $this->errors[$name] = "Le champ {$field['label']} doit être un email valide.";
                // Vérification du champ Message avec un minimum de 10 caractères
            } elseif ($field['type'] === 'textarea' && strlen($value) < 10) {
                return $this->errors[$name] = "Le message {$field['label']} doit contenir au moins 10 caractères.";
                // Vérification du champ Sujet avec une liste de choix
                } 
                elseif ($field['type'] ==='select' &&!in_array($value, $field['attributes']['options'])) 
                {
                return $this->errors[$name] = "Le champ {$field['label']} doit être une option valide.";
                // Vérification du champ Sujet
                } 
                elseif ($field['type'] === 'file') 
                {
                    return $this->validation($name, $field['attributes']);
                }
            }
            return ($this->errors);
        }
        // Si aucune erreur n'est détectée, on retourne true
        return true;
    }
        // Fonction de validation des fichiers 
        private function validation(string $name, array $attributes): void {
            if (!isset($_FILES[$name])) {
                if ($attributes['required'] ?? false) {
                    $this->errors[$name] = "Le fichier {$name} est obligatoire.";
                }
                return;
            }
            $file = $_FILES[$name];
            $types = ['jpeg', 'JPEG', 'png','PNG', 'pdf','PDF'];
            $maxSize = 2097152; 
                
            if (!in_array($file['type'], $types)) {
                $this->errors[$name] = "Le fichier {$name} doit être de type JPEG, PNG ou PDF.";
            } elseif ($file['size'] > $maxSize) {
                $this->errors[$name] = "Le fichier {$name} ne doit pas dépasser 2 Mo.";
            }
        }
        // Fonction getError affichage des erreurs sous chaque champ concerné.
        public function getErrors(): array {
            return $this->errors;
            // Affichage des erreurs
            foreach ($this->errors as $field => $error) {
                echo "<p style='color: red;'>Erreur dans le champ {$field}: {$error}</p>";
            }
            // Vérification de la présence d'erreurs
            return count($this->errors) > 0;
        }
    }

// Test de la fonction
$form = new FormGenerator('/submit', 'POST');


?>