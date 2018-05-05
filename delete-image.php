<?php
header('Access-Control-Allow-Origin: *');

//include("mysqlconnect.php");

// Define database connection parameters
    $hn      = 'localhost';
    $un      = 'root';
    $pwd     = '123456';
    $db      = 'ihelp';
    $cs      = 'utf8';
    
    // Set up the PDO parameters
    $dsn  = "mysql:host=" . $hn . ";port=3306;dbname=" . $db . ";charset=" . $cs;
    $opt  = array(
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                        );
    // Create a PDO instance (connect to the database)
    $pdo  = new PDO($dsn, $un, $pwd, $opt);

    // Sanitise supplied record ID for matching to table record
    $_postId   =  filter_var($_REQUEST['postId'], FILTER_SANITIZE_NUMBER_INT);

    // Attempt to run PDO prepared statement
    try {
       $sql  = "DELETE FROM images_tbl WHERE PostId = :postId";
       $stmt = $pdo->prepare($sql);
       $stmt->bindParam(':postId', $_postId, PDO::PARAM_INT);
       $stmt->execute();

       echo json_encode('Congratulations the record is Deleted');
    }
    // Catch any errors in running the prepared statement
    catch(PDOException $e)
    {
       echo $e->getMessage();
    }

?>;
