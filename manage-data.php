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
   $data    = array();


   // Determine which mode is being requested
   switch($key)
   {

      // Add a new record to the technologies table
      case "create":

         // Sanitise URL supplied values
         $post            = filter_var($_REQUEST['post'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
         $_userId         = filter_var($_REQUEST['userId'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
         $_postedLocation = filter_var($_REQUEST['postedLocation'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
         $_postalCode     = filter_var($_REQUEST['postalCode'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
         $_postedCity     = filter_var($_REQUEST['postedCity'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
         $_postedState    = filter_var($_REQUEST['postedState'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
         $_postedCountry  = filter_var($_REQUEST['postedCountry'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);

         // Attempt to run PDO prepared statement
         try {
            $sql  = "INSERT INTO posts(post, CreatedById, PostedLocation, PostalCode, City, State, Country) VALUES(:post, :CreatedById, :postedLocation, :postalCode, :postedCity, :postedState, :postedCountry)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':post', $post, PDO::PARAM_STR);
            $stmt->bindParam(':CreatedById', $_userId, PDO::PARAM_STR);
            $stmt->bindParam(':postedLocation', $_postedLocation, PDO::PARAM_STR);
            $stmt->bindParam(':postalCode', $_postalCode, PDO::PARAM_STR);
            $stmt->bindParam(':postedCity', $_postedCity, PDO::PARAM_STR);
            $stmt->bindParam(':postedState', $_postedState, PDO::PARAM_STR);
            $stmt->bindParam(':postedCountry', $_postedCountry, PDO::PARAM_STR);
            $stmt->execute();
            $lastId = $pdo->lastInsertId();

            echo json_encode(array('id' => $lastId));
         }
         // Catch any errors in running the prepared statement
         catch(PDOException $e)
         {
            echo $e->getMessage();
         }

      break;

      // Update an existing record in the technologies table
      case "update":
         
         // Sanitise URL supplied values
         $_post   = filter_var($_REQUEST['post'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
         $_recordID      = filter_var($_REQUEST['recordID'], FILTER_SANITIZE_NUMBER_INT);
         $_postedLocation = filter_var($_REQUEST['postedLocation'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
         $_postalCode     = filter_var($_REQUEST['postalCode'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
         $_postedCity     = filter_var($_REQUEST['postedCity'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
         $_postedState    = filter_var($_REQUEST['postedState'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
         $_postedCountry  = filter_var($_REQUEST['postedCountry'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);

         // Attempt to run PDO prepared statement
         try {
            $sql  = "UPDATE posts SET post = :postDesc, PostedLocation = :postedLoc, PostalCode = :postalCode, City = :postedCity, State = :postedState, Country = :postedCountry WHERE id = :recordID";
            $stmt =  $pdo->prepare($sql);
            $stmt->bindParam(':postDesc', $_post, PDO::PARAM_STR);
            $stmt->bindParam(':recordID', $_recordID, PDO::PARAM_INT);
            $stmt->bindParam(':postedLoc', $_postedLocation, PDO::PARAM_STR);
            $stmt->bindParam(':postalCode', $_postalCode, PDO::PARAM_STR);
            $stmt->bindParam(':postedCity', $_postedCity, PDO::PARAM_STR);
            $stmt->bindParam(':postedState', $_postedState, PDO::PARAM_STR);
            $stmt->bindParam(':postedCountry', $_postedCountry, PDO::PARAM_STR);
            $stmt->execute();

            echo json_encode('Congratulations the record was updated');
         }
         // Catch any errors in running the prepared statement
         catch(PDOException $e)
         {
            echo $e->getMessage();
         }

      break;

      // Remove an existing record in the technologies table
      case "delete":

         // Sanitise supplied record ID for matching to table record
         $_recordID   =  filter_var($_REQUEST['recordID'], FILTER_SANITIZE_NUMBER_INT);

         // Attempt to run PDO prepared statement
         try {
            $pdo  = new PDO($dsn, $un, $pwd);
            $sql  = "DELETE FROM posts WHERE id = :recordID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':recordID', $_recordID, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode('Congratulations the record ' . $name . ' was removed');
         }
         // Catch any errors in running the prepared statement
         catch(PDOException $e)
         {
            echo $e->getMessage();
         }

      break;

      // Add Comment to posts
      case "addComment":

         // Sanitise URL supplied values
         $_comment     = filter_var($_REQUEST['comment'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
         $_postId      = filter_var($_REQUEST['postId'], FILTER_SANITIZE_NUMBER_INT);
         $_commentedBy = filter_var($_REQUEST['commentedBy'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);

         // Attempt to run PDO prepared statement
         try {
            $sql  = "INSERT INTO comments(comment, postId, commentedBy) VALUES(:comment, :postId, :commentedBy)";
            $stmt    = $pdo->prepare($sql);
            $stmt->bindParam(':comment', $_comment, PDO::PARAM_STR);
            $stmt->bindParam(':postId', $_postId, PDO::PARAM_INT);
            $stmt->bindParam(':commentedBy', $_commentedBy, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(array('message' => 'Congratulations the record ' . $_comment . ' was added to the database'));
         }
         // Catch any errors in running the prepared statement
         catch(PDOException $e)
         {
            echo $e->getMessage();
         }
      
      break;

      // Update Comment
      case "updateComment":

            // Sanitise URL supplied values
         $_comment   = filter_var($_REQUEST['comment'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
         $_commentId = filter_var($_REQUEST['commentId'], FILTER_SANITIZE_NUMBER_INT);

         // Attempt to run PDO prepared statement
         try {
            $sql  = "UPDATE comments SET comment = :commentDesc WHERE id = :commentId";
            $stmt =  $pdo->prepare($sql);
            $stmt->bindParam(':commentDesc', $_comment, PDO::PARAM_STR);
            $stmt->bindParam(':commentId', $_commentId, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode('Congratulations the record ' . $_commentId . ' was updated');
         }
         // Catch any errors in running the prepared statement
         catch(PDOException $e)
         {
            echo $e->getMessage();
         }

      break;


      // Delete Comment
      case "deleteComment":
      
            // Sanitise URL supplied values
            $_commentId = filter_var($_REQUEST['commentId'], FILTER_SANITIZE_NUMBER_INT);

            // Attempt to run PDO prepared statement
            try {
                  $pdo  = new PDO($dsn, $un, $pwd);
                  $sql  = "DELETE FROM comments WHERE id = :commentId";
                  $stmt = $pdo->prepare($sql);
                  $stmt->bindParam(':commentId', $_commentId, PDO::PARAM_INT);
                  $stmt->execute();
      
                  echo json_encode('Congratulations the record ' . $_commentId . ' was removed');
            }
            // Catch any errors in running the prepared statement
            catch(PDOException $e)
            {
                  echo $e->getMessage();
            }

      break;

      // Add User
      case "addUser":

            // Sanitise URL supplied values
            $_userId     = filter_var($_REQUEST['userId'], FILTER_SANITIZE_STRING);
            $_name       = filter_var($_REQUEST['name'], FILTER_SANITIZE_STRING);
            //$_nickName   = filter_var($_REQUEST['nickName'], FILTER_SANITIZE_STRING);
            $_email      = filter_var($_REQUEST['email'], FILTER_SANITIZE_STRING);
            $_locationId = filter_var($_REQUEST['locationId'], FILTER_SANITIZE_NUMBER_INT);
            $_totalStars = filter_var($_REQUEST['totalStars'], FILTER_SANITIZE_NUMBER_INT);
            $target_path = "uploads/DummyImage.jpg";
            $_postFilter = "CT";

            // Attempt to run PDO prepared statement
            try {
                  // Insert User data in User table in PHP
                  $sql  = "INSERT INTO users(userUid, name, email, photoURL, totalstars, PostFilter, PostalCode) VALUES(:userId, :name, :email, '".$target_path."', :totalstars, '".$_postFilter."', :locationId )";
                  $stmt = $pdo->prepare($sql);
                  $stmt->bindParam(':userId', $_userId, PDO::PARAM_STR);
                  $stmt->bindParam(':name', $_name, PDO::PARAM_STR);
                  //$stmt->bindParam(':nickName', $_nickName, PDO::PARAM_STR);
                  $stmt->bindParam(':email', $_email, PDO::PARAM_STR);
                  $stmt->bindParam(':locationId', $_locationId, PDO::PARAM_INT);
                  $stmt->bindParam(':totalstars', $_totalStars, PDO::PARAM_INT);
                  $stmt->execute();
                  
                  // Insert User image path in images table
                  $imagepathsql = "INSERT INTO images_tbl (images_path, userUid, submission_date) VALUES ('".$target_path."', :userId, '".date("Y-m-d")."')";
                  $stmt = $pdo->prepare($imagepathsql);
                  $stmt->bindParam(':userId', $_userId, PDO::PARAM_STR);
                  $stmt->execute();

                  echo json_encode(array('message' => 'Congratulations the record ' . $_name . ' was added to the database'));
            }
            // Catch any errors in running the prepared statement
            catch(PDOException $e)
            {
                  echo $e->getMessage();
            }

      break;

      // Update User
      case "updateUserPostFilter":

            // Sanitise URL supplied values
            $_userId     = filter_var($_REQUEST['userId'], FILTER_SANITIZE_STRING);
            $_postFilter = filter_var($_REQUEST['postFilter'], FILTER_SANITIZE_STRING);

            // Attempt to run PDO prepared statement
            try {
                  // Insert User data in User table in PHP
                  $sql  = "UPDATE users SET PostFilter = :postFilter WHERE userUid = :userId";
                  $stmt =  $pdo->prepare($sql);
                  $stmt->bindParam(':postFilter', $_postFilter, PDO::PARAM_STR);
                  $stmt->bindParam(':userId', $_userId, PDO::PARAM_STR);
                  $stmt->execute();

                  echo json_encode(array('message' => 'Congratulations the record was updated to the database'));
            }
            // Catch any errors in running the prepared statement
            catch(PDOException $e)
            {
                  echo $e->getMessage();
            }

      break;

      // Update User
      case "updateUserData":

            // Sanitise URL supplied values
            $_userId     = filter_var($_REQUEST['userId'], FILTER_SANITIZE_STRING);
            $_locationId = filter_var($_REQUEST['locationId'], FILTER_SANITIZE_STRING);
            $_userName = filter_var($_REQUEST['userName'], FILTER_SANITIZE_STRING);

            // Attempt to run PDO prepared statement
            try {
                  // Insert User data in User table in PHP , PostalCode =: postalCode
                  $sql  = "UPDATE users SET name = :updatedName, PostalCode = :postalCode WHERE userUid = :userId";
                  $stmt =  $pdo->prepare($sql);
                  $stmt->bindParam(':userId', $_userId, PDO::PARAM_STR);
                  $stmt->bindParam(':updatedName', $_userName, PDO::PARAM_STR);
                  $stmt->bindParam(':postalCode', $_locationId, PDO::PARAM_STR);

                  $stmt->execute();

                  echo json_encode(array('message' => 'Congratulations the record was updated to the database'));
            }
            // Catch any errors in running the prepared statement
            catch(PDOException $e)
            {
                  echo $e->getMessage();
            }

      break;

      // Add Wishlist
      case "addWishlist":

            // Sanitise URL supplied values
            $_postId  = filter_var($_REQUEST['postId'], FILTER_SANITIZE_NUMBER_INT);
            $_userUid = filter_var($_REQUEST['userUid'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);

            // Attempt to run PDO prepared statement
            try {
                  $sql   = "INSERT INTO wishlist(PostId, UserUid) VALUES(:postId, :userUid)";
                  $stmt  = $pdo->prepare($sql);
                  $stmt->bindParam(':postId', $_postId, PDO::PARAM_INT);
                  $stmt->bindParam(':userUid', $_userUid, PDO::PARAM_STR);
                  $stmt->execute();

                  echo json_encode(array('message' => 'Congratulations the record was added to the database'));
            }
            // Catch any errors in running the prepared statement
            catch(PDOException $e)
            {
                  echo $e->getMessage();
            }
      
      break;

      // Delete Wishlist
      case "deleteWishlist":
      
            // Sanitise URL supplied values
            $_postId  = filter_var($_REQUEST['postId'], FILTER_SANITIZE_NUMBER_INT);
            $_userUid = filter_var($_REQUEST['userUid'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);

            // Attempt to run PDO prepared statement
            try {
                  $pdo  = new PDO($dsn, $un, $pwd);
                  $sql  = "DELETE FROM wishlist WHERE UserUid = :userUid AND PostId = :postId";
                  $stmt = $pdo->prepare($sql);
                  $stmt->bindParam(':postId', $_postId, PDO::PARAM_INT);
                  $stmt->bindParam(':userUid', $_userUid, PDO::PARAM_STR);
                  $stmt->execute();

                  echo json_encode('Congratulations the record was removed');
            }
            // Catch any errors in running the prepared statement
            catch(PDOException $e)
            {
                  echo $e->getMessage();
            }
      break;

      // Add like for a post
      case "addLike":

            // Sanitise URL supplied values
            $_postId  = filter_var($_REQUEST['postId'], FILTER_SANITIZE_NUMBER_INT);
            $_userUid = filter_var($_REQUEST['userUid'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);

            // Attempt to run PDO prepared statement
            try {
                  $sql   = "INSERT INTO likes(PostId, UserUid) VALUES(:postId, :userUid)";
                  $stmt  = $pdo->prepare($sql);
                  $stmt->bindParam(':postId', $_postId, PDO::PARAM_INT);
                  $stmt->bindParam(':userUid', $_userUid, PDO::PARAM_STR);
                  $stmt->execute();

                  echo json_encode(array('message' => 'Congratulations the record was added to the database'));
            }
            // Catch any errors in running the prepared statement
            catch(PDOException $e)
            {
                  echo $e->getMessage();
            }
      break;

      // Delete Like
      case "deleteLike":
      
            // Sanitise URL supplied values
            $_postId  = filter_var($_REQUEST['postId'], FILTER_SANITIZE_NUMBER_INT);
            $_userUid = filter_var($_REQUEST['userUid'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);

            // Attempt to run PDO prepared statement
            try {
                  $pdo  = new PDO($dsn, $un, $pwd);
                  $sql  = "DELETE FROM likes WHERE UserUid = :userUid AND PostId = :postId";
                  $stmt = $pdo->prepare($sql);
                  $stmt->bindParam(':postId', $_postId, PDO::PARAM_INT);
                  $stmt->bindParam(':userUid', $_userUid, PDO::PARAM_STR);
                  $stmt->execute();

                  echo json_encode('Congratulations the record was removed');
            }
            // Catch any errors in running the prepared statement
            catch(PDOException $e)
            {
                  echo $e->getMessage();
            }
      break;
   }

?>