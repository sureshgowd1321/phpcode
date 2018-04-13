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

    // Load Parameters
    $_mincount = $_GET['minCount'];
    $_userUid  = $_GET['userUid'];
    $_loadType = $_GET['loadType'];

    $_limitValue = 4;

    // SQL Query Design
    $sql = "SELECT * FROM posts WHERE CreatedById = :userId ";

    if( $_loadType === 'initialload' ){

        $sql .= "ORDER BY ID DESC limit ";
        $sql .= $_limitValue;

        $stmt = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    } elseif( $_loadType === 'scroll' ) {
        
        $sql .= "AND ID < :minCount ";

        $sql .= "ORDER BY ID DESC limit ";
        $sql .= $_limitValue;

        $stmt = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        // Binding Parameters
        $stmt-> bindParam(':minCount', $_mincount, PDO::PARAM_STR);   
    }

    // Bind Parameters
    $stmt-> bindParam(':userId', $_userUid, PDO::PARAM_STR);

    // Execute SQL
    $stmt -> execute();

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