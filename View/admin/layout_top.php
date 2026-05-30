<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drivo | Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/.Dual/Proyecto_Final.V2/View/admin/css/admin.css">
    <link rel="shortcut icon" href="/.Dual/Proyecto_Final.V2/View/img/logo.png" type="image/x-icon">
</head>
<body>
<div class="admin-wrapper">

    <aside class="admin-sidebar">
        <div class="admin-logo">
            <img src="/.Dual/Proyecto_Final.V2/View/img/logo.png" alt="Drivo">
            <span>Panel Admin</span>
        </div>

        <nav class="admin-nav">
            <span class="nav-section-label">General</span>
            <a href="/.Dual/Proyecto_Final.V2/Controller/admin/dashboard.php"
               class="<?= $paginaActiva === 'dashboard' ? 'active' : '' ?>">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            <span class="nav-section-label">Gestión</span>
            <a href="/.Dual/Proyecto_Final.V2/Controller/admin/vehiculos.php"
               class="<?= $paginaActiva === 'vehiculos' ? 'active' : '' ?>">
                <i class="bi bi-car-front-fill"></i> Vehículos
            </a>
            <a href="/.Dual/Proyecto_Final.V2/Controller/admin/reservas_admin.php"
               class="<?= $paginaActiva === 'reservas' ? 'active' : '' ?>">
                <i class="bi bi-calendar-check-fill"></i> Reservas
            </a>
            <a href="/.Dual/Proyecto_Final.V2/Controller/admin/clientes_admin.php"
               class="<?= $paginaActiva === 'clientes' ? 'active' : '' ?>">
                <i class="bi bi-people-fill"></i> Clientes
            </a>
        </nav>

        <div class="admin-sidebar-footer">
            <a href="/.Dual/Proyecto_Final.V2/Controller/index.php" class="back-link">
                <i class="bi bi-arrow-left-circle"></i> Volver a la web
            </a>
            <a href="/.Dual/Proyecto_Final.V2/Controller/logout.php">
                <i class="bi bi-box-arrow-right"></i> Cerrar sesión
            </a>
        </div>
    </aside>

    <main class="admin-main">
        <div class="admin-topbar">
            <h1><?= $tituloPagina ?? 'Panel de Administración' ?></h1>
            <div class="topbar-right">
                <span class="admin-badge-user">
                    <i class="bi bi-shield-fill-check"></i>
                    <?= htmlspecialchars($_SESSION['nombre'] ?? 'Admin') ?>
                </span>
            </div>
        </div>
        <div class="admin-content">
