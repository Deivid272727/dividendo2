<?php

session_start();

require_once 'vendor/autoload.php';
require_once 'bd/conexion.php';

/*
|--------------------------------------------------------------------------
| PON AQUÍ TU CLIENT ID DE GOOGLE 
|--------------------------------------------------------------------------
*/
$CLIENT_ID = '1088139853006-cdps7llshndaa283unf04bt3nhq5a1pu.apps.googleusercontent.com';

$client = new Google_Client([
    'client_id' => $CLIENT_ID
]);

if (!isset($_POST['credential'])) {
    exit("Token no recibido");
}

$credential = $_POST['credential'];

try {

    $payload = $client->verifyIdToken($credential);

    if (!$payload) {
        exit("No se pudo verificar la cuenta de Google");
    }

    $googleId = $payload['sub'];
    $email    = $payload['email'];
    $name     = $payload['name'];

    $avatar = isset($payload['picture'])
        ? $payload['picture']
        : 'logo3.png';

    /*
    |--------------------------------------------------------------------------
    | BUSCAR USUARIO POR EMAIL
    |--------------------------------------------------------------------------
    */
    $stmt = $pdo->prepare(
        "SELECT * FROM users WHERE Email = ?"
    );

    $stmt->execute([$email]);

    $user = $stmt->fetch();

    /*
    |--------------------------------------------------------------------------
    | SI NO EXISTE, LO CREAREMOS
    |--------------------------------------------------------------------------
    */
    if (!$user) {

        $stmt = $pdo->prepare(
            "INSERT INTO users
            (Name, Email, Password, Avatar, GoogleID, LoginType)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        $stmt->execute([
            $name,
            $email,
            '',
            $avatar,
            $googleId,
            'google'
        ]);

        $userId = $pdo->lastInsertId();

        $stmt = $pdo->prepare(
            "SELECT * FROM users WHERE id = ?"
        );

        $stmt->execute([$userId]);

        $user = $stmt->fetch();
    }
    else {

        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR DATOS DE GOOGLE
        |--------------------------------------------------------------------------
        */
        $stmt = $pdo->prepare(
            "UPDATE users
             SET GoogleID = ?, LoginType = 'google'
             WHERE id = ?"
        );

        $stmt->execute([
            $googleId,
            $user['id']
        ]);

        $stmt = $pdo->prepare(
            "SELECT * FROM users WHERE id = ?"
        );

        $stmt->execute([
            $user['id']
        ]);

        $user = $stmt->fetch();
    }

    /*
    |--------------------------------------------------------------------------
    | CREAR SESIÓN
    |--------------------------------------------------------------------------
    */
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['Name'];
    $_SESSION['user_email'] = $user['Email'];
    $_SESSION['user_avatar'] = $user['Avatar'];

    echo "success";

} catch (Exception $e) {

    echo "Error: " . $e->getMessage();

}