<?php

    require_once( 'db/db.php' );

    // Create user

    if( isset( $_POST['submit'] ) && !empty( $_POST['login'] ) && !empty( $_POST['password'] ) && !empty( $_POST['confirmPassword'] )) {


        if ( $_POST['password'] == $_POST['confirmPassword'] ) {

            if ( preg_match( '/^(\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*){6,20}$/', $_POST['password'] ) ) {

                $login = $_POST['login'];
                $password = password_hash( $_POST['password'], PASSWORD_BCRYPT );
                $id = rand(2, 100);

                try {
                    $user = array (
                        'login' => $login,
                        'password' => $password,
                        'id' => $id
                    );

                    $single_insert = new MongoDB\Driver\BulkWrite();
                    $single_insert->insert( $user );

                    $manager->executeBulkWrite( 'Blog.users', $single_insert );

                    echo '<meta http-equiv="refresh" content="0; URL=index.php">';

                } catch ( \MongoDB\Driver\Exception\BulkWriteException $e ) {
                    echo $e->getMessage();
                }

            } else {
                $message = "The password must contain at least 6 characters";
            }

        } else {
            $message = "Passwords are not identical";
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

        <form class="box" method="POST" action="singup.php">
            <h1>Sing up</h1>
            <input type="text" name="login" placeholder="Login">
            <input type="password" name="password" placeholder="Password">
            <input type="password" name="confirmPassword" placeholder="Confirm Password">
            <input type="submit" name="submit" value="Sing up">
            <p class="connectionError"><?php if ( isset($message) ) echo $message; ?></p>
            <h1><a href="index.php">Sing in</a></h1>
        </form>

    </body>

</html>