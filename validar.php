<?php
$Email=$_POST[ 'Email' ];
$Password=$_POST[ 'Password' ];
session_start();
$_SESSION['Name' ]=$Name;

include( 'db.php' );

$consulta="SELECT*FROM users where Email='$Email' and Password='$Password'";
$resultado=mysqli_query($conexion,$consulta) ;

$filas=mysqli_num_rows($resultado);

if($Filas){
        header("Location:Index.php" );
    } else{
    ?>
    <?php 
    include("Login.php");
    ?>
    <h1 class = "bad"> Error en la autentificacion</h1> 
    <?php 
}
mysqli_free_result($resultado);
mysqli_close($conexion);
