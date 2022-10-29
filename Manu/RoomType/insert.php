<?php 

    session_start();
    require_once "../../connect.php";

    if (isset($_POST['submit'])) {
        $name = $_POST['name'];

            try {
        
                if (!isset($_SESSION['error'])) {
                    $sql = $conn->prepare("INSERT INTO roomtype(name) VALUES(:name);");
                    $sql->bindParam(":name", $name);
        
                    $sql->execute();

                    if ($sql) {
                        $_SESSION['success'] = "Data has been inserted successfully";
                        header("location: roomtype.php");
                    } else {
                        $_SESSION['error'] = "Data has not been inserted successfully";
                        header("location: roomtype.php");
                    }
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }    
    }

?>