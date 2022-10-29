<?php 

    session_start();
    require_once "../../connect.php";

    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $detail = $_POST['detail'];
        $days = $_POST['days'];
        $start = $_POST['start'];
        $stop = $_POST['stop'];
        $price= $_POST['price'];
        $roomtype = $_POST['roomtype'];
        $date1=date("Y-m-d H:i:s",strtotime($start));
        $date2=date("Y-m-d H:i:s",strtotime($stop));
        $status = $_POST['status'];

            try {
        
                if (!isset($_SESSION['error'])) {
                    $sql = $conn->prepare("INSERT INTO package(name, detail, days, start, stop, price, roomtype_id, status) VALUES(:name, :detail, :days, :start, :stop, :price, :roomtype_id, :status);");
                    $sql->bindParam(":name", $name);
                    $sql->bindParam(":detail", $detail);
                    $sql->bindParam(":days", $days);
                    $sql->bindParam(":start", $date1);
                    $sql->bindParam(":stop", $date2);
                    $sql->bindParam(":price", $price);
                    $sql->bindParam(":roomtype_id", $roomtype);
                    $sql->bindParam(":status", $status);
                    
                    $sql->execute();

                    if ($sql) {
                        $_SESSION['success'] = "Data has been inserted successfully";
                        header("location: package.php");
                    } else {
                        $_SESSION['error'] = "Data has not been inserted successfully";
                        header("location: package.php");
                    }
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }    
    }

?>