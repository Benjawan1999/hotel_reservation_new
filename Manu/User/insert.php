<?php 

session_start();
require_once "../../connect.php";

if (isset($_POST['submit'])) {
    $bank = $_POST['bank'];
    $amount = $_POST['amount'];
    $img = $_FILES['img'];
    $id = $_POST['id'];

  
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
                    $sql = $conn->prepare("INSERT INTO payment(bank, pay_date, pay_total, booking_id, img) VALUES(:bank, now(), :pay_total, :id, :img)");
                    $sql->bindParam(":bank", $bank);
                    $sql->bindParam(":pay_total", $amount);
                    $sql->bindParam(":id", $id);
                    $sql->bindParam(":img", $fileNew);
                    $sql->execute();

                    $sql2 = $conn->prepare("UPDATE booking SET status = 1 WHERE id = :id");
                    $sql2->bindParam(":id", $id);
                    $sql2->execute();

                    if ($sql) {
                        $_SESSION['success'] = "Data has been inserted successfully";
                        header("location: mybooking.php");
                    } else {
                        $_SESSION['error'] = "Data has not been inserted successfully";
                        header("location: payment.php?id=".$id);
                    }
                }
            }
        }
}


?>