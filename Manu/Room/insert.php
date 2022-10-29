<?php 

session_start();
require_once "../../connect.php";

if (isset($_POST['submit'])) {
    $roomtype = $_POST['roomtype'];
    $price= $_POST['price'];
    $detail = $_POST['detail'];
    $img = $_FILES['img'];

        $allow = array('jpg', 'jpeg', 'png');
        $extension = explode('.', $img['name']);
        $fileActExt = strtolower(end($extension));
        $fileNew = rand() . "." . $fileActExt; 
        $filePath = '/Images/'.$fileNew;
        $dirpath = realpath(dirname(getcwd()));

        if (in_array($fileActExt, $allow)) {
            if ($img['size'] > 0 && $img['error'] == 0) {
                //echo $dirpath.$filePath;
                if (move_uploaded_file($img['tmp_name'], $dirpath.$filePath)) {
                    $sql = $conn->prepare("INSERT INTO room(roomtype_id, price, detail, img) VALUES(:roomtype_id, :price, :detail, :img)");
                    $sql->bindParam(":roomtype_id", $roomtype);
                    $sql->bindParam(":price", $price);
                    $sql->bindParam(":detail", $detail);
                    $sql->bindParam(":img", $fileNew);
                    $sql->execute();

                    if ($sql) {
                        $_SESSION['success'] = "Data has been inserted successfully";
                        header("location: room.php");
                    } else {
                        $_SESSION['error'] = "Data has not been inserted successfully";
                        header("location: booked.php");
                    }
                }
            }
        }
}


?>