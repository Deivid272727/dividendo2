<<?php
session_start();


if (!isset($_SESSION['user_name'])) {
    $_SESSION['user_name'] = "Invitado";
    $_SESSION['user_email'] = "ola@hotmail.com";
    $_SESSION['user_avatar'] = "Default.png"; 
}
?>
DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="Css/Style.css">
    <link href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet">
    <title>Dividendo la venganza - Inicio</title>

    <!-- Esta cosa es el CSS del circulito del perfil -->
    <style>
        .header-perfil-contenedor {
            position: absolute;
            top: 15px;
            right: 25px;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            z-index: 1000;
            font-family: 'Poppins', sans-serif;
        }

        .btn-avatar-disparador {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            cursor: pointer;
            object-fit: cover;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .btn-avatar-disparador:hover {
            border-color: #00E676; 
            box-shadow: 0 0 10px rgba(0, 230, 118, 0.5);
        }

        .cuadro-perfil-flotante {
            display: none; 
            position: absolute;
            top: 55px;
            right: 0;
            width: 290px;
            background-color: #ffffff;
            border-radius: 20px;
            box-shadow: 0px 8px 30px rgba(0, 0, 0, 0.5);
            padding: 25px 20px;
            text-align: center;
            box-sizing: border-box;
            animation: fadeIn 0.2s ease-in-out;
        }

        .cuadro-perfil-flotante.mostrar-menu {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .avatar-grande-cuadro {
            width: 85px;
            height: 85px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 12px;
            border: 3px solid #00E676;
        }

        .texto-saludo-cuadro {
            font-size: 18px;
            font-weight: 600;
            color: #1d1d1d;
            margin: 0;
        }

        .texto-email-cuadro {
            font-size: 13px;
            color: #666666;
            margin: 5px 0 18px 0;
        }

        .btn-accion-editar {
            display: inline-block;
            text-decoration: none;
            background-color: transparent;
            color: #1d1d1d;
            border: 1px solid #cccccc;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            width: 85%;
            box-sizing: border-box;
        }

        .btn-accion-editar:hover {
            background-color: #f1f3f4;
            border-color: #b0b0b0;
        }

        .linea-separadora-cuadro {
            border: 0;
            border-top: 1px solid #e8eaed;
            margin: 20px 0 10px 0;
        }

        .btn-accion-salir {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            color: #3c4043;
            font-size: 14px;
            font-weight: 500;
            padding: 10px;
            border-radius: 10px;
            transition: background-color 0.2s;
        }

        .btn-accion-salir:hover {
            background-color: #f7f8f9;
            color: #d93025; 
        }
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
                <span class="name">Y esta mafudada?</span>
                <span class="profession">No se bro</span>
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
                    <a href="Perfil.php">
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
                <span class="mode-text text"> Modo Oscuro</span>
                <div class="toggle-switch">
                    <span class="switch"></span>
                </div>
            </li>
        </div>
    </div>
</nav>

<section class="home">
    
  <div class="header-perfil-contenedor">
    <img src="<?php echo $_SESSION['user_avatar']; ?>" alt="Perfil" class="btn-avatar-disparador" id="avatar-disparador-id">

    <div class="cuadro-perfil-flotante" id="cuadro-perfil-id">
        <img src="<?php echo $_SESSION['user_avatar']; ?>" alt="Avatar Grande" class="avatar-grande-cuadro">
        <h3 class="texto-saludo-cuadro">¡Hola, <?php echo $_SESSION['user_name']; ?>!</h3>
        <p class="texto-email-cuadro"><?php echo $_SESSION['user_email']; ?></p>
        
        <a href="Profile.php" class="btn-accion-editar">Administrar tu Cuenta</a>
        
        <hr class="linea-separadora-cuadro">
        
        <a href="Logout.php" class="btn-accion-salir">
            <i class='bx bx-log-out'></i> Salir
        </a>
    </div>
</div>

    <div class="text">Casitaaa</div>
</section>

<script src="script.js"></script>

<script>
    const avatarDisparador = document.getElementById('avatar-disparador-id');
    const cuadroPerfil = document.getElementById('cuadro-perfil-id');

    avatarDisparador.addEventListener('click', (e) => {
        cuadroPerfil.classList.toggle('mostrar-menu');
        e.stopPropagation(); 
    });

    document.addEventListener('click', (e) => {
        if (!cuadroPerfil.contains(e.target) && e.target !== avatarDisparador) {
            cuadroPerfil.classList.remove('mostrar-menu');
        }
    });
</script>
</body>
</html>