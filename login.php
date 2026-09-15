<?php
session_start();
require_once 'bd/conexion.php'; // Tu archivo de conexión PDO

$error_msg = "";
$success_msg = "";

// ==========================================
// LOGICA DE REGISTRO (Sign Up)
// ==========================================
if (isset($_POST['register_btn'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Asignamos el archivo por defecto que guardaste en tu carpeta
    $foto_defecto = "logo2.png"; 

    if (!empty($name) && !empty($email) && !empty($password)) {
        // Encriptamos la contraseña por seguridad
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        try {
            // Verificar si el correo ya existe
            $stmt = $pdo->prepare("SELECT id FROM users WHERE Email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                $error_msg = "El correo ya está registrado, lince.";
            } else {
                // MODIFICADO: Ahora inserta en la columna 'Avatar'
                $stmt = $pdo->prepare("INSERT INTO users (Name, Email, Password, Avatar) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $email, $passwordHash, $foto_defecto]);
                $success_msg = "¡Registro exitoso! Ya puedes iniciar sesión.";
            }
        } catch (PDOException $e) {
            $error_msg = "Error en el registro: " . $e->getMessage();
        }
    }
}

// ==========================================
// LOGICA DE INICIO DE SESIÓN (Sign In)
// ==========================================
if (isset($_POST['login_btn'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        try {
            // Buscamos al usuario por Email
            $stmt = $pdo->prepare("SELECT * FROM users WHERE Email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            // Si existe el usuario y la contraseña coincide
            if ($user && password_verify($password, $user['Password'])) {
                // Guardamos los datos en la sesión para que los use tu menú flotante
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['Name'];
                $_SESSION['user_email'] = $user['Email'];
                
                // MODIFICADO: Jalamos el valor de la columna 'Avatar' de la base de datos
                $_SESSION['user_avatar'] = $user['Avatar']; 

                // Redirigimos a la página de inicio
                header("Location: Index.php");
                exit;
            } else {
                $error_msg = "Credenciales incorrectas de prro :v";
            }
        } catch (PDOException $e) {
            $error_msg = "Error en el login: " . $e->getMessage();
        }
    }
}
?>



<!DOCTYPE html>
<html lang = "es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="EI=edge">


<!--================ CSS =============-->
<link rel="stylesheet" href="Css/Login.css">

<script src="https://accounts.google.com/gsi/client" async defer></script>


<!--======== Boxicons CSS ============-->
<link href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet">
<link rel= "stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css"/>


<title> Dividendo la venganza</title>
</head>
<body>

 
    
    <!-- Aqui empieza el codigo y el header del sidebard-->
<nav class="sidebar close">
    <header>
        <div class="image-text">
            <span class="image">
                <img src="logo.png" alt="logo">
            </span>
          <div class="text header-text">
                <span class="name">Y esta mafudada?</span>
                <span class="profession">No se bro</span>
            </div>
        </div>
        <i class ="bx bx-chevron-right toggle"></i> <!-- Esta cosa es el boton del side bar osea el > -->
    </header>
 
    <!-- Aqui estan los segmentos del sidebar-->
         <!-- Busqueda-->
    <div class="menu-bar">
        <div class="menu">
             <li class="search-box">
                        <i class="bx bx-search icon"></i>
                        <input type="search" placeholder="Search...">
                    </a>
                        <!-- nose-->
                </li>
            <ul class="menu-links">
                <li class="nav-link">
                    <a href="Index.php">
                        <i class="bx bx-home-alt icon"></i>
                        <span class="text nav-text">Casitá</span>
                    </a>
                        <!-- Aqui estan los segmentos del sidebar-->
                              </li>
            <ul class="menu-links">
                <li class="nav-link">
                    <a href="Perfil.php">
                        <i class="bx bx-home-alt icon"></i>
                        <span class="text nav-text">Perfil</span>
                    </a>
                             <!-- Aqui estan los segmentos del sidebar-->
                                   </li>
            <ul class="menu-links">
                <li class="nav-link">
                    <a href="#">
            <i class='bx bxs-tree-alt icon'></i>
                
            
                        <span class="text nav-text">Garcianos</span>
                    </a>
                                  <!-- Aqui estan los segmentos del sidebar-->
                                        </li>
            <ul class="menu-links">
                <li class="nav-link">
                    <a href="#">
                        <i class="bx bx-heart icon"></i>
                        <span class="text nav-text">Santa chad</span>
                    </a>

                        <!-- nose-->

                         </li>
            <ul class="menu-links">
               
            </ul>
        </div> 


        <div class="bottom-content">

         <li class="">
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

</nav><!-- aqui termina -->   


<!-- Header de la pagina -->
<section class = "home">
   
<?php if(!empty($error_msg)): ?>
    <div style="color: red; text-align: center; margin-bottom: 10px; font-weight: bold;"><?php echo $error_msg; ?></div>
<?php endif; ?>

<?php if(!empty($success_msg)): ?>
    <div style="color: green; text-align: center; margin-bottom: 10px; font-weight: bold;"><?php echo $success_msg; ?></div>
<?php endif; ?>

<!-- Registrarse -->
    <div class = "container" id="container">
      <div class="form-container sign-up">
    <form action="login.php" method="POST">
        <h1>Create Account</h1>
        <div class="social-icons">
            <a href="#" class="icon"><i class="fa-brands fa-google.plus-g"></i></a>
        </div>
        <span>or use your email for registration</span>
        
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" 
               pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$"
               title="Debe tener mínimo 8 caracteres, una mayúscula, una minúscula, un número y un símbolo"
               required>

               <!--Aqui es donde nos vendes tu alma -->       
        <table>
            <tr>
                <td><input type="checkbox" name="privacidad" id="privacidad" required></td>
                <td>He leído los <a href="privacidad.php" target="_blank">Terminos y condiciones</a></td>
            </tr>
        </table>
        <button type="submit" name="register_btn">Sign Up</button> 
    </form>
</div>

<div class="form-container sign-in">
    <form action="login.php" method="POST">
        <h1>Sign In</h1>
        <div id="g_id_onload"
         data-client_id="1088139853006-cdps7llshndaa283unf04bt3nhq5a1pu.apps.googleusercontent.com"
         data-callback="handleCredentialResponse">
        </div>

        <div class="g_id_signin"
         data-type="standard">
        </div>
    
        <span>or use your email password</span>
        
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        
        <a href="#">Olvidaste tu contraseña papu? :´v </a>

        <button type="submit" name="login_btn">Sign In</button> 
    </form>
</div>


       <div class="toggle-container">
       <div class="toggle">
        <div class="toggle-panel toggle-left"> 
            <h1> bIENVENIDO PLEBE </h1>
            <p> No se papu ahi dice otra cosa </p>
            <button class="hidden" id= "login"> Sign In</button>
            </div>


             <div class="toggle-panel toggle-right"> 
            <h1> Que rollo hermano</h1>
            <p> No se papu ahi dice otra cosa x2 </p>
            <button class="hidden" id= "register"> Sign Up</button>
            </div>
        </div>
    </div>
</div>

</section>


<script>
function handleCredentialResponse(response) {

    fetch("google_login.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "credential=" + response.credential
    })
    .then(res => res.text())
    .then(data => {

        if(data === "success"){
            window.location.href = "Index.php";
        } else {
            alert(data);
        }

    });

}
</script>
 <script src="script2.js"></script>
</body>

</html>