<?php
session_start();
require_once 'bd/conexion.php';

// Si no ha iniciado sesión, usamos tu ID 3 por defecto
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 3; 
}

$id_usuario = $_SESSION['user_id'];

// 1. Jalamos todos los datos del usuario desde tu tabla 'users' (incluyendo la columna fondo)
$stmtUser = $pdo->prepare("SELECT Name, Email, Avatar, fondo FROM users WHERE id = ?");
$stmtUser->execute([$id_usuario]);
$usuario = $stmtUser->fetch();

if ($usuario) {
    $_SESSION['user_name'] = $usuario['Name'];
    $_SESSION['user_email'] = $usuario['Email'];
    $_SESSION['user_avatar'] = !empty($usuario['Avatar']) ? $usuario['Avatar'] : 'uploads/default_avatar.png';
    $_SESSION['user_fondo'] = !empty($usuario['fondo']) ? $usuario['fondo'] : 'uploads/default_banner.png';
} else {
    $_SESSION['user_name'] = "Invitado";
    $_SESSION['user_email'] = "correo@ejemplo.com";
    $_SESSION['user_avatar'] = "uploads/default_avatar.png";
    $_SESSION['user_fondo'] = "uploads/default_banner.png";
}

// 2. Jalamos la galería del expositor
$cuadro1 = "uploads/default_game.png";
$cuadro2 = "uploads/default_game.png";

$stmtExpo = $pdo->prepare("SELECT numero_cuadro, imagen FROM exposition WHERE id_usuario = ?");
$stmtExpo->execute([$id_usuario]);
$imagenes_guardadas = $stmtExpo->fetchAll();

foreach ($imagenes_guardadas as $row) {
    if ($row['numero_cuadro'] == 1) {
        $cuadro1 = $row['imagen'];
    } elseif ($row['numero_cuadro'] == 2) {
        $cuadro2 = $row['imagen'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="Css/Profile.css">
    <link href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet">
    <title>Dividendo la venganza - Perfil</title>

  <style>
    /* Renderiza el banner dinámico limpio SIN la barra oscura de abajo */
/* Oculta el icono de imagen rota y estiliza el recuadro */
.showcase-item img {
    position: relative;
    background-color: #1b2838; /* Color de fondo idéntico a tu interfaz */
    min-height: 150px;         /* Dale una altura mínima para que no se colapse */
}

/* Reemplaza el texto alternativo por un diseño más limpio */
.showcase-item img::before {
    content: "Agregar Imagen";
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background-color: #1b2838;
    color: #4a5d6e;
    font-size: 14px;
    font-weight: bold;
}

    .profile-header {
        background-image: url('<?php echo $_SESSION['user_fondo']; ?>?t=<?php echo time(); ?>');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    
    /* El resto de tus estilos se quedan exactamente igual */
    .contenedor-expositor {
        display: flex;
        gap: 20px;
        background: #101216;
        padding: 20px;
        border-radius: 5px;
        max-width: 700px;
        margin: 50px auto;
    }
    .cuadro-expositor {
        flex: 1;
        background: #1b2838;
        border: 1px solid #2a475e;
        padding: 10px;
        text-align: center;
        border-radius: 4px;
    }
    .cuadro-expositor img {
        width: 100%;
        height: auto;
        max-height: 200px;
        object-fit: cover;
        border-radius: 2px;
        display: block;
        margin-bottom: 10px;
    }
    input[type="file"] { display: none; }
</style>
</head>
<body>

<nav class="sidebar close">
    <header>
        <div class="image-text">
            <span class="image">
                <img src="logo3.png" alt="logo">
            </span>
            <div class="text header-text">
                <span class="name">Dividendo</span>
                <span class="profession">Beta</span>
            </div>
        </div>
        <i class="bx bx-chevron-right toggle"></i>
    </header>
 
    <div class="menu-bar">
        <div class="menu">
             <li class="search-box">
                <i class="bx bx-search icon"></i>
                <input type="search" placeholder="Search...">
             </li>
            <ul class="menu-links">
                <li class="nav-link">
                    <a href="Index.php">
                        <i class="bx bx-home-alt icon"></i>
                        <span class="text nav-text">Casitá</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="Profile.php">
                        <i class="bx bx-home-alt icon"></i>
                        <span class="text nav-text">Perfil</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="#">
                        <i class='bx bxs-tree-alt icon'></i>
                        <span class="text nav-text">Garcianos</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="#">
                        <i class="bx bx-heart icon"></i>
                        <span class="text nav-text">Santa chad</span>
                    </a>
                </li>
            </ul>
        </div> 

        <div class="bottom-content">
            <li>
                <a href="Login.php">
                    <i class="bx bx-log-in icon"></i>
                    <span class="text nav-text">Iniciar Sesión</span>
                </a>
            </li>
            <li class="mode">
                <div class="moon-sun">
                    <i class="bx bx-moon icon moon"></i>
                    <i class="bx bx-sun icon sun"></i>
                </div>
                <span class="mode-text text"> Dark Mode</span>
                <div class="toggle-switch">
                    <span class="switch"></span>
                </div>
            </li>
        </div>
    </div>
</nav>

<section class="home">
    
    <!-- MENÚ FLOTANTE SUPERIOR DERECHO -->
    <div class="header-perfil-contenedor" style="float: right; margin: 20px; position: relative; z-index: 999;">
        <img src="<?php echo $_SESSION['user_avatar']; ?>?t=<?php echo time(); ?>" alt="Perfil" class="btn-avatar-disparador" id="avatar-disparador-id" style="width: 40px; height: 40px; border-radius: 50%; cursor: pointer;">

        <div class="cuadro-perfil-flotante" id="cuadro-perfil-id" style="display: none; position: absolute; right: 0; background: #1a1d24; padding: 20px; border-radius: 8px; width: 250px;">
            <img src="<?php echo $_SESSION['user_avatar']; ?>?t=<?php echo time(); ?>" alt="Avatar Grande" class="avatar-grande-cuadro" style="width: 80px; height: 80px; border-radius: 50%; display:block; margin: 0 auto 10px;">
            <h3 class="texto-saludo-cuadro" style="text-align: center; color: #fff;">¡Hola, <?php echo $_SESSION['user_name']; ?>!</h3>
            <p class="texto-email-cuadro" style="text-align: center; color: #aaa; font-size: 12px;"><?php echo $_SESSION['user_email']; ?></p>
            
            <a href="Profile.php" class="btn-accion-editar" style="display: block; text-align: center; margin: 10px 0; color: #5c7e10;">Administrar tu Cuenta</a>
            <hr style="border: 0; border-top: 1px solid #333;">
            <a href="Logout.php" class="btn-accion-salir" style="display: block; text-align: center; color: #ff4a4a;">
                <i class='bx bx-log-out'></i> Salir
            </a>
        </div>
    </div>

    <div style="clear: both;"></div>

<!-- REEMPLAZA ÚNICAMENTE ESTE CONTENEDOR EN TU PROFILE.PHP -->
<div class="profile-container">
  <div class="profile-header" id="profileHeader">
    <div class="profile-info-wrapper" style="display: flex; align-items: center; gap: 20px;">
      
      <!-- FORMULARIO DEL AVATAR (Ahora con inline-block para que no empuje el texto) -->
      <form action="Procesar_perfil.php" method="POST" enctype="multipart/form-data" style="display: inline-block; margin: 0;">
        <input type="hidden" name="accion" value="avatar"> 
        <label class="profile-avatar-label" title="Cambiar foto de perfil" style="cursor: pointer; display: block;">
          <div class="profile-avatar">
            <img src="<?php echo $_SESSION['user_avatar']; ?>?t=<?php echo time(); ?>" id="avatarImg" alt="Avatar">
          </div>
          <input type="file" name="archivo_perfil" accept="image/*" onchange="this.form.submit()" hidden>
        </label>
      </form>

      <!-- DETALLES DEL USUARIO (Regresa a su flujo original al lado de la foto) -->
      <div class="profile-user-details">
        <h2 id="userNameDisplay" style="margin: 0; white-space: nowrap;"><?php echo $_SESSION['user_name']; ?></h2>
        <p id="userEmailDisplay" style="margin: 5px 0 0 0; white-space: nowrap;"><?php echo $_SESSION['user_email']; ?></p>
      </div>
    </div>
    
    <!-- SECCIÓN DE ACCIONES (Alineación perfecta de botones usando flexbox) -->
    <div class="profile-actions" style="display: flex; align-items: center; gap: 10px;">
      <form action="Procesar_perfil.php" method="POST" enctype="multipart/form-data" style="margin: 0; display: inline-block;">
        <input type="hidden" name="accion" value="fondo"> 
        <label class="btn-secondary" style="cursor: pointer; display: inline-block; margin: 0; white-space: nowrap;">
          Cambiar Fondo 
          <input type="file" name="archivo_perfil" accept="image/*" onchange="this.form.submit()" hidden>
        </label>
      </form>
      <button class="btn-primary" id="openModalBtn" style="margin: 0; white-space: nowrap;">Modificar Perfil</button>
    </div>
  </div>

<div class="showcase-section">
    <div class="showcase-title">Mii galeria</div>
    <div class="showcase-grid">
      
      <!-- CUADRO 1 -->
      <div class="showcase-item">
        <form action="Procesar_Expositor.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="numero_cuadro" value="1">
          <label class="showcase-upload-label" title="Subir imagen del juego">
            <img src="<?php echo $cuadro1; ?>" id="gameImg1" alt="Juego 1">
            <input type="file" name="imagen_archivo" class="game-input" accept="image/*, image/gif" onchange="this.form.submit()" hidden>
          </label>
        </form>
      </div>

      <!-- CUADRO 2 -->
      <div class="showcase-item">
        <form action="Procesar_Expositor.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="numero_cuadro" value="2">
          <label class="showcase-upload-label" title="Subir imagen del juego">
            <img src="<?php echo $cuadro2; ?>" id="gameImg2" alt="Juego 2">
            <input type="file" name="imagen_archivo" class="game-input" accept="image/*, image/gif" onchange="this.form.submit()" hidden>
          </label>
        </form>
      </div>

    </div>
</div>

<!-- MODAL DE TEXTOS -->
<div class="modal-overlay" id="profileModal">
  <div class="modal-content">
    <h3>Modificar Perfil</h3>
    
    <form action="Actualizar_Datos.php" method="POST">
      <div class="form-group">
        <label for="inputName">Nombre de usuario</label>
        <input type="text" id="inputName" name="nuevo_nombre" value="<?php echo $_SESSION['user_name']; ?>" required>
      </div>
      <div class="form-group">
        <label for="inputEmail">Correo electrónico</label>
        <input type="email" id="inputEmail" name="nuevo_email" value="<?php echo $_SESSION['user_email']; ?>" required>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn-secondary" id="closeModalBtn">Cancelar</button>
        <button type="submit" class="btn-primary" id="saveProfileBtn">Guardar Cambios</button>
      </div>
    </form>
    
  </div>
</div>

</section>

<script src="ProfileScript.js"></script>

</body>
</html>
