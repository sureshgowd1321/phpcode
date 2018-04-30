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

  // Retrieve specific parameter from supplied URL
  $key  = strip_tags($_REQUEST['key']);
  $data = array();
                
  // Determine which mode is being requested
  switch($key)
  {
    // Add a new record to the technologies table
    case "totalCommentsPerPost":

      // Attempt to query database table and retrieve data commentedDate
      try {

        $_postId = $_GET['postId'];

        $sql = "SELECT * FROM comments ";

        $sql .= "Where postId = :postId ORDER BY ID ASC";

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

    break;

    // Add a new record to the technologies table
    case "countOfCommentsPerPost":

      // Attempt to query database table and retrieve data
      try {

        $_postId = $_GET['postId'];

        $sql = "SELECT count(*) FROM comments ";

        $sql .= "Where postId = :postId";

        $stmt = $pdo->prepare($sql); 

        $stmt->bindParam(':postId', $_postId, PDO::PARAM_INT);

        $stmt -> execute();

        $number_of_rows = $stmt->fetchColumn();

        // Return data as JSON
        echo json_encode($number_of_rows);
      }
      catch(PDOException $e)
      {
        echo $e->getMessage();
      }

    break;

  }

  


?>