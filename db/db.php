<?php
    try { 
        $manager = new MongoDB\Driver\Manager( "mongodb://localhost:27017" );
    }catch ( \MongoDB\Driver\Exception\InvalidArgumentException $e ){
        echo $e->getMessage();
    }
