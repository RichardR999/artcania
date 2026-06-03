<?php
if (!Auth::check() || !Auth::isAdmin()) {
    header('Location: ' . url('login')); exit;
}
$user = Auth::user();
$cfg  = artcania_config();
$flash_success = isset($flash_success) ? $flash_success : (Session::getFlash('success') ?: '');
$flash_error   = isset($flash_error)   ? $flash_error   : (Session::getFlash('error')   ?: '');
$uri  = ltrim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
$base = ltrim(parse_url($cfg['url'], PHP_URL_PATH), '/');
$page = $base ? ltrim(substr($uri, strlen($base)), '/') : $uri;
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($pageTitle) ? e($pageTitle).' – ' : '' ?>Admin · Artcania</title>
  <link rel="stylesheet" href="<?= asset('css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/fontawesome.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/sweetalert2.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
  <style>
    body::before, body::after { display:none !important; }
    .artcania-main {
      position:relative !important;
      z-index:10 !important;
      background: #0a0612 !important;
      min-height: 100vh;
    }
    /* SweetAlert2 tema oscuro artcania */
    .swal2-popup {
      background: #16162a !important;
      border: 1px solid rgba(124,58,237,.3) !important;
      border-radius: 16px !important;
      color: #f0eaff !important;
    }
    .swal2-title { color: #e8c65a !important; font-family: 'Cinzel', serif !important; }
    .swal2-html-container { color: #f0eaff !important; }
    .swal2-confirm {
      background: linear-gradient(135deg,#7c3aed,#5b21b6) !important;
      border-radius: 10px !important;
    }
    .swal2-cancel {
      background: rgba(248,113,113,.15) !important;
      color: #f87171 !important;
      border: 1px solid rgba(248,113,113,.3) !important;
      border-radius: 10px !important;
    }
    .swal2-icon.swal2-warning { border-color: #e8c65a !important; color: #e8c65a !important; }
    .swal2-icon.swal2-success { border-color: #34d399 !important; color: #34d399 !important; }
    .swal2-icon.swal2-error   { border-color: #f87171 !important; color: #f87171 !important; }
  </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="artcania-sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="sidebar-brand-text">✦ ARTCANIA</div>
    <div class="sidebar-subtitle">Panel de Administración</div>
  </div>

  <nav class="sidebar-nav">
    <div class="sidebar-section-label">Principal</div>
    <a href="<?= url('admin/dashboard') ?>" class="sidebar-nav-item <?= $page==='admin/dashboard'||$page==='admin'?'active':'' ?>">
      <i class="fa fa-chart-pie"></i> Dashboard
    </a>
    <a href="<?= url('admin/usuarios') ?>" class="sidebar-nav-item <?= strpos($page,'admin/usuarios')===0?'active':'' ?>">
      <i class="fa fa-users"></i> Usuarios
    </a>
    <a href="<?= url('admin/gestion-roles') ?>" class="sidebar-nav-item <?= strpos($page,'admin/gestion-roles')===0?'active':'' ?>">
      <i class="fa fa-shield-halved"></i> Roles
    </a>

    <div class="sidebar-section-label">Contenido</div>
    <a href="<?= url('admin/gestion-artistas') ?>" class="sidebar-nav-item <?= strpos($page,'admin/gestion-artistas')===0?'active':'' ?>">
      <i class="fa fa-palette"></i> Artistas
    </a>
    <a href="<?= url('admin/gestion-curadores') ?>" class="sidebar-nav-item <?= strpos($page,'admin/gestion-curadores')===0?'active':'' ?>">
      <i class="fa fa-masks-theater"></i> Curadores
    </a>
    <a href="<?= url('admin/gestion-obras') ?>" class="sidebar-nav-item <?= strpos($page,'admin/gestion-obras')===0?'active':'' ?>">
      <i class="fa fa-image"></i> Obras
    </a>
    <a href="<?= url('admin/gestion-subastas') ?>" class="sidebar-nav-item <?= strpos($page,'admin/gestion-subastas')===0?'active':'' ?>">
      <i class="fa fa-gavel"></i> Subastas
    </a>
    <a href="<?= url('admin/gestion-fanarts') ?>" class="sidebar-nav-item <?= strpos($page,'admin/gestion-fanarts')===0?'active':'' ?>">
      <i class="fa fa-star"></i> FanArts
    </a>
    <a href="<?= url('admin/gestion-exposiciones') ?>" class="sidebar-nav-item <?= strpos($page,'admin/gestion-exposiciones')===0?'active':'' ?>">
      <i class="fa fa-landmark"></i> Exposiciones
    </a>

    <div class="sidebar-section-label">Sistema</div>
    <a href="<?= url('admin/bitacora') ?>" class="sidebar-nav-item <?= strpos($page,'admin/bitacora')===0?'active':'' ?>">
      <i class="fa fa-scroll"></i> Logs
    </a>
    <a href="<?= url('admin/respaldos') ?>" class="sidebar-nav-item <?= strpos($page,'admin/respaldos')===0?'active':'' ?>">
      <i class="fa fa-database"></i> Respaldos
    </a>
    <a href="<?= url('admin/configuracion') ?>" class="sidebar-nav-item <?= strpos($page,'admin/configuracion')===0?'active':'' ?>">
      <i class="fa fa-gear"></i> Configuración
    </a>

    <div class="sidebar-section-label">Mi cuenta</div>
    <a href="<?= url('perfil') ?>" class="sidebar-nav-item <?= strpos($page,'perfil')===0?'active':'' ?>">
      <i class="fa fa-user"></i> Mi Perfil
    </a>
    <a href="<?= url('notificaciones') ?>" class="sidebar-nav-item <?= strpos($page,'notificaciones')===0?'active':'' ?>">
      <i class="fa fa-bell"></i> Notificaciones
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="sidebar-user-info">
      <img src="<?= avatar($user['avatar'] ?? '') ?>" alt="<?= e($user['nombre']) ?>">
      <div>
        <div class="user-name"><?= e($user['nombre']) ?></div>
        <div class="user-role">Administrador</div>
      </div>
    </div>
    <a href="<?= url('logout') ?>" class="btn btn-sm w-100 mt-2"
       style="background:rgba(248,113,113,.12);color:#f87171;border:1px solid rgba(248,113,113,.25);border-radius:10px;font-size:.78rem">
      <i class="fa fa-right-from-bracket me-1"></i>Cerrar sesión
    </a>
  </div>
</aside>

<!-- MAIN -->
<div class="artcania-main">

  <!-- Top bar -->
  <div class="d-flex align-items-center justify-content-between mb-4">
    <button class="btn d-lg-none me-2" id="sidebarToggle"
            style="background:var(--card-bg);border:1px solid var(--border);color:var(--pearl);border-radius:10px;padding:.4rem .7rem">
      <i class="fa fa-bars"></i>
    </button>
    <div>
      <?php if($flash_success): ?>
        <div class="alert-success-magic d-flex align-items-center gap-2 py-2 px-3">
          <i class="fa fa-circle-check"></i> <?= e($flash_success) ?>
        </div>
      <?php endif; ?>
      <?php if($flash_error): ?>
        <div class="alert-danger-magic d-flex align-items-center gap-2 py-2 px-3">
          <i class="fa fa-circle-exclamation"></i> <?= e($flash_error) ?>
        </div>
      <?php endif; ?>
    </div>
    <div class="d-flex align-items-center gap-3 ms-auto">
      <a href="<?= url('') ?>" class="btn btn-sm btn-outline-magic">
        <i class="fa fa-arrow-up-right-from-square me-1"></i>Ver sitio
      </a>
      <img src="<?= avatar($user['avatar'] ?? '') ?>"
           class="navbar-avatar" style="width:38px;height:38px" alt="">
    </div>
  </div>

  <!-- Page content -->
  <?= $content ?>

</div>

<!-- Sidebar overlay mobile -->
<div class="d-lg-none" id="sidebarOverlay"
     style="display:none!important;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:1019"></div>

<script>var BASE_URL = <?= json_encode(rtrim($cfg['url'], '/')) ?>;</script>
<script src="<?= asset('js/jquery.min.js') ?>"></script>
<script src="<?= asset('js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= asset('js/sweetalert2.min.js') ?>"></script>
<script src="<?= asset('js/main.js') ?>"></script>
<script src="<?= asset('js/admin.js') ?>"></script>
<script>
$('#sidebarToggle').on('click', function(){
  $('#sidebar').toggleClass('show');
  $('#sidebarOverlay').toggle();
});
$('#sidebarOverlay').on('click', function(){
  $('#sidebar').removeClass('show');
  $(this).hide();
});

// Flash messages con SweetAlert2
<?php if($flash_success): ?>
Swal.fire({
  toast: true,
  position: 'top-end',
  icon: 'success',
  title: <?= json_encode($flash_success) ?>,
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  background: '#16162a',
  color: '#f0eaff',
  iconColor: '#34d399'
});
<?php endif; ?>
<?php if($flash_error): ?>
Swal.fire({
  toast: true,
  position: 'top-end',
  icon: 'error',
  title: <?= json_encode($flash_error) ?>,
  showConfirmButton: false,
  timer: 4000,
  timerProgressBar: true,
  background: '#16162a',
  color: '#f0eaff',
  iconColor: '#f87171'
});
<?php endif; ?>
</script>
</body>
</html>
