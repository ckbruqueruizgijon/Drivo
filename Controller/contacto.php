<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cargamos la vista de contacto
include '../View/contacto/contacto_view.php';