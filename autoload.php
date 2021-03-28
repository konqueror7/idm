<?php
spl_autoload_register(function ($class_name) {
    require $_SERVER['DOCUMENT_ROOT'] . '/classes' . '/' . str_replace("\\", "/", $class_name) . '.php';
});
?>
