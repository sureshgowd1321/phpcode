<?php
    header('Access-Control-Allow-Origin: *');
    
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
    $data;

    // Attempt to query database table and retrieve data
    try {

        $_postId = $_GET['postId'];

        $sql = "SELECT * FROM posts ";

        $sql .= "Where id = :postId";
        $stmt = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $stmt -> execute(array(':postId' => $_postId));

        $data = $stmt->fetch(PDO::FETCH_OBJ);
        
        // Return data as JSON
        echo json_encode($data);

    } 
    catch(PDOException $e)
    {
       echo $e->getMessage();
    }           
?>