<?php

require_once('db/db.php');

// Connection

if ( isset( $_POST['submitLogin'] ) && !empty( $_POST['login'] ) && !empty( $_POST['password'] ) ) {

    $login = $_POST['login'];
    $password = $_POST['password'];

    try {

        $filter = ['login' => $login];
        $option = [];

        $read = new MongoDB\Driver\Query( $filter, $option );
        $users = $manager->executeQuery( 'Blog.users', $read );

        $pass = "";
        $id = "";

        foreach ( $users as $user ) {
            $pass = $user->password;
            $id = $user->id;
        }

        if ( password_verify( $password, $pass ) ) {

            session_start();
            $_SESSION['id'] = $id;
            $_SESSION['login'] = $login;
            echo '<meta http-equiv="refresh" content="0; URL=home.php">';

        } else {

            $message = "<h3 style='color: rgb(226,226,226);'>Incorrect password. </h3>";

        }

    } catch (MongoDB\Driver\ConnectionException $e) {
        echo $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link type="text/css" rel="stylesheet" href="assets/css/style.css">
    </head>

    <body>

        <form class="box" method="POST" action="index.php">
            <h1>Connection</h1>
            <input type="text" name="login" placeholder="Login">
            <input type="password" name="password" placeholder="Password">
            <input type="submit" name="submitLogin" value="Login">
            <p class="connectionError"><?php if ( isset($message) ) echo $message; ?></p>
            <h1><a href="singup.php">Sing up</a></h1>
        </form>

    </body>

</html>