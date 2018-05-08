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

        $_userPostFilter  = $_GET['userPostFilter'];
        $_userCity        = $_GET['postedCity'];
        $_userState       = $_GET['postedState'];
        $_userCountry     = $_GET['postedCountry'];
      //  $_userUid         = $_GET['userUid'];

        // How many records per page
        $rpp = 4;
        $page = $_GET['page'];

        // Check for page 1
        if($page > 1){
            $start = ($page * $rpp) -$rpp;
        }else{
            $start = 0;
        }

        // SQL Query Design
        $sql = "SELECT * FROM posts "; // WHERE CreatedById = :userId
        
        // Filter posts based on User opted filter
        if( $_userPostFilter === 'CT' ) {

            // If User opted filter as City
            $sql .= "WHERE City = :userCity ";

        } elseif( $_userPostFilter === 'ST' ) {

            // If User opted filter as State
            $sql .= "WHERE State = :userState ";

        } elseif( $_userPostFilter === 'CNTY' ) {

            // If User opted filter as Country
            $sql .= "WHERE Country = :userCountry ";

        }
        
        // SQL Order by and it's limit offset value
        $sql .= "ORDER BY ID DESC LIMIT $start, $rpp";

        // Preparing SQL
        $stmt = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        
        // Binding Parameters
        //$stmt-> bindParam(':userId', $_userUid, PDO::PARAM_STR);

        if( $_userPostFilter === 'CT' ) {
            $stmt-> bindParam(':userCity', $_userCity, PDO::PARAM_STR);
        }elseif( $_userPostFilter === 'ST' ) {
            $stmt-> bindParam(':userState', $_userState, PDO::PARAM_STR);
        }elseif( $_userPostFilter === 'CNTY' ) {
            $stmt-> bindParam(':userCountry', $_userCountry, PDO::PARAM_STR);
        }

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