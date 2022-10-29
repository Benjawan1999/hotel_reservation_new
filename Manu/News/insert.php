<?php 

session_start();
require_once "../../connect.php";

if (isset($_POST['submit'])) {
    $newsname = $_POST['newsname'];
    $newsdetail = $_POST['newsdetail'];
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
                    $sql = $conn->prepare("INSERT INTO new(newsname, newsdetail, img) VALUES(:newsname, :newsdetail, :img)");
                    $sql->bindParam(":newsname", $newsname);
                    $sql->bindParam(":newsdetail", $newsdetail);
                    $sql->bindParam(":img", $fileNew);
                    $sql->execute();

                    if ($sql) {
                        $_SESSION['success'] = "Data has been inserted successfully";
                        header("location: news.php");
                    } else {
                        $_SESSION['error'] = "Data has not been inserted successfully";
                        header("location: news.php");
                    }
                }
            }
        }
}


?>