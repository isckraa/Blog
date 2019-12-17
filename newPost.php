<?php
    require_once('db/db.php');

    if ( session_status() !== PHP_SESSION_ACTIVE && !isset( $_SESSION ) ) {
        session_start();
    }

    if ( isset( $_SESSION['login'] ) ) {

        $pseudo = $_SESSION['login'];

        // Create new post
        if ( isset( $_POST['submitPost'] ) && !empty( $_POST['subject'] ) && !empty( $_POST['message'] ) ) {

            $idPost = rand( 2, 1000 );
            $subject = $_POST['subject'];
            $author = $_SESSION['login'];
            $message = $_POST['message'];
            $date = date( 'm/d/Y h:i:s', time() );

            $newPost = array (
                'idPost' => $idPost,
                'subject' => $subject,
                'author' => $author,
                'message' => $message,
                'date' => $date
            );

            $single_insert = new MongoDB\Driver\BulkWrite();
            $single_insert->insert( $newPost );

            $manager->executeBulkWrite( 'Blog.post', $single_insert );
            $message = "Your message have been saved";
        }
    } else {
        header( "Refresh: 1; url=404.html" );
    }

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="assets/css/style.css">
        <title>Home</title>
    </head>

    <body>
        <nav>
            <div class="logo">
                <h4>Blog</h4>
            </div>
            <ul class="nav-links">
                <li><a href="home.php">Home</a></li>
                <li>Connect as : <?php echo $pseudo ?> </li>
                <li><a href="logout.php">Disconnect</a></li>
            </ul>
            <div class="burger">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
        </nav>

        <div class="container">
            <section class="newPost">

                <form class="formPost" method="post" action="newPost.php">
                    <h1>Add new post</h1>
                    <input type="text" name="subject" placeholder="Subject">
                    <textarea name="message" placeholder="Message"></textarea>
                    <input type="submit" name="submitPost" value="Submit">
                    <h3><?php if ( isset( $message ) ) echo $message; ?></h3>
                </form>

            </section>
        </div>

        <script src="assets/js/app.js"></script>
    </body>
</html>
