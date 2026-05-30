<?php
require_once 'auth_admin.php';
require_once '../../Model/Cliente.php';

$clientes = Cliente::getAll();
$paginaActiva = 'clientes';
include '../../View/admin/clientes_view.php';
