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


    function GetImageExtension($imagetype)
   	 {
       if(empty($imagetype)) return false;
       switch($imagetype)
       {
           case 'image/bmp': return '.bmp';
           case 'image/gif': return '.gif';
           case 'image/jpeg': return '.jpg';
           case 'image/png': return '.png';
           default: return false;
       }
     }
	 
	 
    if (!empty($_FILES["file"]["name"])) {
        
        $imgtype=$_FILES["file"]["type"];
        $ext= GetImageExtension($imgtype);
        $imagename=date("d-m-Y")."-".time().$ext;
        //$target_path = $imagename;
        $target_path = "uploads/".$imagename;
        $_postId = $_POST['postId'];

        if(move_uploaded_file($_FILES["file"]["tmp_name"], $target_path)) {

            // $sql  = "UPDATE images_tbl SET images_path = '".$target_path."', submission_date = '".date("Y-m-d")."' WHERE userUid = '".$_userUid. "' ";
            $sql  = "INSERT INTO images_tbl(images_path, PostId) VALUES(:targetPath, :postId)";
            $stmt = $pdo->prepare($sql);

            // Bind Parameters
            $stmt->bindParam(':targetPath', $target_path, PDO::PARAM_STR);
            $stmt->bindParam(':postId', $_postId, PDO::PARAM_INT);

            $stmt->execute();
            
        }else{
            exit("Error While uploading image on the server, Name: ");
        } 

    }

?>;
