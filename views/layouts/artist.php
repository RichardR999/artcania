<?php
if (!Auth::check() || !in_array(Auth::rol(), ['artista','admin'])) {
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
  <title><?= isset($pageTitle) ? e($pageTitle).' – ' : '' ?>Artista · Artcania</title>
  <link rel="stylesheet" href="<?= asset('css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/fontawesome.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/artista.css') ?>">
</head>
<body>

<aside class="artcania-sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="sidebar-brand-text">✦ ARTCANIA</div>
    <div class="sidebar-subtitle">Portal del Artista</div>
  </div>

  <nav class="sidebar-nav">
    <a href="<?= url('artista/dashboard') ?>" class="sidebar-nav-item <?= $page==='artista/dashboard'?'active':'' ?>">
      <i class="fa fa-house"></i> Dashboard
    </a>
    <a href="<?= url('artista/obras') ?>" class="sidebar-nav-item <?= strpos(\$page,'artista/obras')===0?'active':'' ?>">
      <i class="fa fa-images"></i> Mis Obras
    </a>
    <a href="<?= url('artista/subir') ?>" class="sidebar-nav-item <?= $page==='artista/subir'?'active':'' ?>">
      <i class="fa fa-cloud-arrow-up"></i> Subir Obra
    </a>
    <a href="<?= url('artista/metricas') ?>" class="sidebar-nav-item <?= strpos(\$page,'artista/metricas')===0?'active':'' ?>">
      <i class="fa fa-chart-line"></i> Métricas
    </a>
    <a href="<?= url('artista/fanarts') ?>" class="sidebar-nav-item <?= strpos(\$page,'artista/fanarts')===0?'active':'' ?>">
      <i class="fa fa-star"></i> FanArts
    </a>
    <a href="<?= url('artista/colaboraciones') ?>" class="sidebar-nav-item <?= strpos(\$page,'artista/colaboraciones')===0?'active':'' ?>">
      <i class="fa fa-handshake"></i> Colaboraciones
    </a>
    <a href="<?= url('chat') ?>" class="sidebar-nav-item <?= strpos(\$page,'chat')===0?'active':'' ?>">
      <i class="fa fa-comments"></i> Chat
    </a>
    <a href="<?= url('artista/estadisticas') ?>" class="sidebar-nav-item <?= strpos(\$page,'artista/estadisticas')===0?'active':'' ?>">
      <i class="fa fa-chart-bar"></i> Estadísticas
    </a>
    <a href="<?= url('artista/editar-perfil') ?>" class="sidebar-nav-item <?= strpos(\$page,'artista/editar')===0?'active':'' ?>">
      <i class="fa fa-user-pen"></i> Mi Perfil
    </a>
    <a href="<?= url('artista/contactos') ?>" class="sidebar-nav-item <?= strpos(\$page,'artista/contactos')===0?'active':'' ?>">
      <i class="fa fa-envelope"></i> Contactos
    </a>

    <div class="sidebar-section-label">Mi cuenta</div>
    <a href="<?= url('perfil') ?>" class="sidebar-nav-item <?= strpos(\$page,'perfil')===0?'active':'' ?>">
      <i class="fa fa-user"></i> Mi Perfil
    </a>
    <a href="<?= url('notificaciones') ?>" class="sidebar-nav-item <?= strpos(\$page,'notificaciones')===0?'active':'' ?>">
      <i class="fa fa-bell"></i> Notificaciones
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="sidebar-user-info">
      <img src="<?= avatar($user['avatar'] ?? '') ?>" alt="<?= e($user['nombre']) ?>">
      <div>
        <div class="user-name"><?= e($user['nombre']) ?></div>
        <div class="user-role" style="color:var(--purple-mid)">Artista</div>
      </div>
    </div>
    <a href="<?= url('logout') ?>" class="btn btn-sm w-100 mt-2"
       style="background:rgba(248,113,113,.12);color:#f87171;border:1px solid rgba(248,113,113,.25);border-radius:10px;font-size:.78rem">
      <i class="fa fa-right-from-bracket me-1"></i>Salir
    </a>
  </div>
</aside>

<div class="artcania-main">
  <div class="d-flex align-items-center mb-4 gap-2">
    <button class="btn d-lg-none" id="sidebarToggle"
            style="background:var(--card-bg);border:1px solid var(--border);color:var(--pearl);border-radius:10px;padding:.4rem .7rem">
      <i class="fa fa-bars"></i>
    </button>
    <?php if($flash_success): ?>
      <div class="alert-success-magic flex-grow-1 py-2 px-3 d-flex align-items-center gap-2">
        <i class="fa fa-circle-check"></i> <?= e($flash_success) ?>
      </div>
    <?php endif; ?>
    <?php if($flash_error): ?>
      <div class="alert-danger-magic flex-grow-1 py-2 px-3 d-flex align-items-center gap-2">
        <i class="fa fa-circle-exclamation"></i> <?= e($flash_error) ?>
      </div>
    <?php endif; ?>
    <div class="ms-auto d-flex align-items-center gap-2">
      <a href="<?= url('') ?>" class="btn btn-sm btn-outline-magic">
        <i class="fa fa-globe me-1"></i>Ver sitio
      </a>
      <img src="<?= avatar($user['avatar'] ?? '') ?>" class="navbar-avatar"
           style="width:38px;height:38px" alt="">
    </div>
  </div>

  <?= $content ?>
</div>

<div class="d-lg-none" id="sidebarOverlay"
     style="display:none!important;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:1019"></div>

<script>var BASE_URL = <?= json_encode(rtrim($cfg['url'], '/')) ?>;</script>
<script src="<?= asset('js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= asset('js/jquery.min.js') ?>"></script>
<script src="<?= asset('js/main.js') ?>"></script>
<script>
$('#sidebarToggle').on('click',function(){ $('#sidebar').toggleClass('show'); $('#sidebarOverlay').toggle(); });
$('#sidebarOverlay').on('click',function(){ $('#sidebar').removeClass('show'); $(this).hide(); });
</script>
</body>
</html>

