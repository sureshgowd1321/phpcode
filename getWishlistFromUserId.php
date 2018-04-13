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

    $_limitValue = 4;

    $_userUid = $_GET['userId'];
    $_mincount = $_GET['minCount'];
    $_loadType = $_GET['loadType'];

    $sql = "SELECT * FROM wishlist ";

    $sql .= "Where UserUid = :userId ";

    if( $_loadType === 'noload' ){

        $sql .= "ORDER BY id DESC limit ";
        $sql .= $_limitValue;

        $stmt = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        
    }else{
        if( $_loadType === 'initialload' ){

            $sql .= "ORDER BY id DESC limit ";
            $sql .= $_limitValue;
    
            $stmt = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    
        } elseif( $_loadType === 'scroll' ){
            
            $sql .= "AND id < :minCount ";
    
            $sql .= "ORDER BY id DESC limit ";
            $sql .= $_limitValue;
    
            $stmt = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    
            // Binding Parameters
            $stmt-> bindParam(':minCount', $_mincount, PDO::PARAM_STR);
    
        }
    }

    $stmt-> bindParam(':userId', $_userUid, PDO::PARAM_STR);

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