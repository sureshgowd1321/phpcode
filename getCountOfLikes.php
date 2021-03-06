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
      // Get the Count of likes for each Post
      case "totalLikesCountPerPost":

          // Attempt to query database table and retrieve data
          try {

            $_postId = $_GET['postId'];

            $sql = "SELECT count(*) FROM likes WHERE PostId = :postId"; 

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

      // Get the Count of dislikes for each Post
      case "totalDislikesCountPerPost":

          // Attempt to query database table and retrieve data
          try {

            $_postId = $_GET['postId'];

            $sql = "SELECT count(*) FROM dislikes WHERE PostId = :postId"; 

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

      // Get likes of each user and each post
      case "likesPerUser":

          // Attempt to query database table and retrieve data
          try {

            $_userUid = $_GET['userId'];
            $_postId = $_GET['postId'];

            $sql = "SELECT count(*) FROM likes WHERE UserUid = :userId AND PostId = :postId"; 

            $stmt = $pdo->prepare($sql); 

            $stmt->bindParam(':userId', $_userUid, PDO::PARAM_STR);
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

      // Get dislikes of each user and each post
      case "dislikesPerUser":

          // Attempt to query database table and retrieve data
          try {

            $_userUid = $_GET['userId'];
            $_postId = $_GET['postId'];

            $sql = "SELECT count(*) FROM dislikes WHERE UserUid = :userId AND PostId = :postId"; 

            $stmt = $pdo->prepare($sql); 

            $stmt->bindParam(':userId', $_userUid, PDO::PARAM_STR);
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

      // Get Total likes of each Post
      case "totalLikesPerPost":

          // Attempt to query database table and retrieve data
          try {

            $_postId = $_GET['postId'];

            $sql = "SELECT * FROM likes Where PostId = :postId";

            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':postId', $_postId, PDO::PARAM_INT);

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

      break;

      // Get Total dislikes of each Post
      case "totalDislikesPerPost":

          // Attempt to query database table and retrieve data
          try {

            $_postId = $_GET['postId'];

            $sql = "SELECT * FROM dislikes Where PostId = :postId";

            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':postId', $_postId, PDO::PARAM_INT);

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

      break;
  }

?>