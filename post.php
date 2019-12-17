<?php
    require_once('db/db.php');

    if ( session_status() !== PHP_SESSION_ACTIVE && !isset( $_SESSION ) ) {
        session_start();
    }

    if ( isset( $_SESSION['login'] ) ) {

        // Attributes
        $pseudo = $_SESSION['login'];
        $title = $_GET['title'];
        $date = date('m/d/Y h:i:s', time() );
        $idPost = $_GET['idPost'];
        $message = "";

        // Query selector
        $queryPosts = new MongoDB\Driver\Query( [] );
        $posts = $manager->executeQuery( 'Blog.post', $queryPosts );
        $posts = $posts->toArray();

        foreach ( $posts as $post ) {
            if ( $post->idPost == $idPost ) {
                $message = $post->message;
            }
        }

        // Request for answers
        $queryResponses = new MongoDB\Driver\Query( [] );
        $responses = $manager->executeQuery( 'Blog.response', $queryResponses );
        $responses = $responses->toArray();

        // Request for the answers of the answers
        $queryResponsesOfResponses = new MongoDB\Driver\Query( [] );
        $responsesOfResponses = $manager->executeQuery( 'Blog.responseOfResponse', $queryResponsesOfResponses );
        $responsesOfResponses = $responsesOfResponses->toArray();

        
        // Adding an answer
        if ( isset( $_POST['submitResponse'] ) && !empty( $_POST['messageResponse'] ) ) {

            // Attributes
            $idResponse = rand( 2, 1000 );
            $author = $_SESSION['login'];
            $messageResponse = $_POST['messageResponse'];

            // Prepare response before insertion
            $response = array(
                'idPost' => $idPost,
                'idResponse' => $idResponse,
                'author' => $author,
                'message' => $messageResponse,
                'date' => $date
            );

            // Insert response in db
            $single_insert = new MongoDB\Driver\BulkWrite();
            $single_insert->insert($response);
            $manager->executeBulkWrite('Blog.response', $single_insert);

            // Refresh the page
            header("Refresh: 1; url=post.php?idPost=" . $idPost . "&name=" . $author . "&title=" . $title . "");
        }

        // Adding a response to a response
        if ( isset( $_POST['submitResponseOfResponse'] ) && !empty( $_POST['messageResponseOfResponse'] ) ) {

            // Attributes
            $idResponse = $_GET['idResponse'];
            $idResponseOfResponse = rand( 2, 1000 );
            $author = $_SESSION['login'];
            $messageResponseOfResponse = $_POST['messageResponseOfResponse'];

            // Prepare response before insertion
            $responseOfResponse = array (
                'idResponse' => $idResponse,
                'idResponseOfResponse' => $idResponseOfResponse,
                'author' => $author,
                'message' => $messageResponseOfResponse,
                'date' => $date
            );

            // Insert response in db
            $single_insert = new MongoDB\Driver\BulkWrite();
            $single_insert->insert( $responseOfResponse );
            $manager->executeBulkWrite('Blog.responseOfResponse', $single_insert);

            // Refresh the page
            header("Refresh: 1; url=post.php?idPost=$idPost&name=$author&title=$title");

        }

    } else {
        header("Refresh: 1; url=404.html");
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
                <li>Connect as : <?php echo $pseudo ?></li>
                <li><a href="logout.php">Disconnect</a></li>
            </ul>
            <div class="burger">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
        </nav>

        <div class="container">
            <section class="displayResponses">
                <h3><?php echo $title; ?></h3>

                <div class="subject">
                    <div class="headPost">
                        <h3>Subject : <strong><?php echo $title; ?></strong></h3>
                        <span><?php echo $date; ?></span>
                    </div>

                    <div class="message">
                        <p><?php echo $message; ?></p>
                    </div>



                </div>

                <div class="responses">

                    <?php
                        foreach ( $responses as $response ) {
                            $idPostRes = $response->idPost;
                            $authorRes = $response->author;
                            $messageRes = $response->message;
                            $dateRes = $response->date;

                            if ( $idPost == $idPostRes ) {
                                $idRes = $response->idResponse;
                                ?>

                                <div class="response">

                                    <div class="headResponse">
                                        <h3>Response from <strong><?php echo $authorRes; ?></strong></h3>
                                        <span><?php echo $dateRes; ?></span>
                                    </div>

                                    <div class="messageResponse">
                                        <p><?php echo $messageRes; ?></p>
                                    </div>

                                </div>

                                <?php
                                    foreach ( $responsesOfResponses as $responseOfResponse ) {

                                        $idResponse = $response->idResponse;
                                        $idResResOfRes = $responseOfResponse->idResponse;
                                        $authorResOfRes = $responseOfResponse->author;
                                        $messageResOfRes = $responseOfResponse->message;
                                        $dateResOfRes = $responseOfResponse->date;

                                        if ($idResponse == $idResResOfRes) {

                                            $idResponseOfRes = $responseOfResponse->idResponseOfResponse;

                                ?>
                                            <div class="responseOfResponse">

                                                <div class="headResponseOfResponse">
                                                    <h3>Response from <strong><?php echo $authorResOfRes; ?></strong></h3>
                                                    <span><?php echo $dateResOfRes; ?></span>
                                                </div>

                                                <div class="messageResponse">
                                                    <p><?php echo $messageResOfRes; ?></p>
                                                </div>

                                            </div>
                                <?php
                                        }
                                    }
                                ?>

                                <form class="formResponseOfResponse" method="post" action="post.php?idPost=<?php echo $idPost; ?>&name=<?php echo $pseudo; ?>&title=<?php echo $title; ?>&idResponse=<?php echo $idRes; ?>">
                                    <textarea name="messageResponseOfResponse"></textarea>
                                    <input type="submit" name="submitResponseOfResponse" value="Submit">
                                </form>

                                <?php
                            }
                        }
                    ?>
                </div>

                <form class="formResponse" method="post" action="post.php?idPost=<?php echo $idPost; ?>&name=<?php echo $pseudo; ?>&title=<?php echo $title; ?>">
                    <textarea name="messageResponse"></textarea>
                    <input type="submit" name="submitResponse" value="Submit">
                </form>

            </section>
        </div>

        <script src="assets/js/app.js"></script>
    </body>
</html>
