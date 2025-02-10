<?php
require_once 'class_formgenerator.php';

$form = new FormGenerator($_SERVER['PHP_SELF'], "POST");

$form->addField("name", "text", "Nom", [
    'required' => true,
    'class' => 'input-text',
    'id' => 'name-field'
]);

$form->addField("email", "email", "Email", [
    'required' => true,
    'class' => 'input-email',
    'id' => 'email-field'
]);

$form->addField("message", "textarea", "Message", [
    'required' => true,
    'class' => 'textarea-message',
    'id' => 'message-field'
]);

$form->addField("subject", "select", "Sujet", [
    'required' => true,
    'options' => ["Question", "Problème technique", "Autre"],
    'class' => 'select-subject',
    'id' => 'subject-field'
]);

$form->addField("file_upload", "file", "Télécharger un fichier", [
    'required' => true,
    'class' => 'input-file',
    'id' => 'file-upload'
]);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($form->handleSubmission()) {
        echo "<p style='color: green;'>Le formulaire a été soumis avec succès !</p>";
    } else {
        echo "<p style='color: red;'>Veuillez corriger les erreurs ci-dessous.</p>";
    }
}

$form->render();
?>