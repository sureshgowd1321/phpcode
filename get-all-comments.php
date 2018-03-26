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
  $data = array();


  // Attempt to query database table and retrieve data
  try {

    $_postId = $_GET['postId'];

    $sql = "SELECT Id, postId, comment, commentedBy, commentedDate FROM comments ";

    $sql .= "Where postId = :postId ORDER BY commentedDate ASC";
    $stmt = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt -> execute(array(':postId' => $_postId));

    while($row  = $stmt->fetch(PDO::FETCH_OBJ))
    {
        // Assign each row of data to associative array
        $data[] = $row;
    }

    // Return data as JSON
    echo json_encode($data);
  }
  catch(PDOException $e)
  {
     echo $e->getMessage();
  }


?>