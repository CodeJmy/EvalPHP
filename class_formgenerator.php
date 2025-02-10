<?php
require_once 'interface_form.php';
class FormGenerator implements FormGeneratorInterface{
    private string $name;
    private string $action;
    private string $method;
    private array $fields = [];
}
?>