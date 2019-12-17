<?php
    require_once( 'db/db.php' );

    if ( session_status() !== PHP_SESSION_ACTIVE && !isset( $_SESSION ) ) {
        session_start();
    }

    if ( isset( $_SESSION['login'] ) ) {

        $pseudo = $_SESSION['login'];

        $queryPosts = new MongoDB\Driver\Query([]);
        $posts = $manager->executeQuery( 'Blog.post', $queryPosts );
        $posts = $posts->toArray();

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
            <section class="displaySubjects">

                <div class="addSubject">
                    <h3>Subjects</h3>
                    <h3 class="createPost"><a href="newPost.php">Create a post</a></h3>
                </div>

                <ul class="list">
                    <?php
                        foreach ( $posts as $post ) {

                            $author = $post->author;
                            $id = $post->idPost;
                            $title = $post->subject;
                            $date = $post->date;

                            echo '<li><div class="row"><div class="cellsLink"><a href="post.php?idPost='.$id.'&author='.$author.'&title='.$title.'"><h3>'.$title.'</h3></a><span>Par <strong>'.$author.'</strong> '.$date.'</span></div></div></li>';
                        }
                    ?>
                </ul>

            </section>
        </div>

        <script src="assets/js/app.js"></script>
    </body>
</html>
