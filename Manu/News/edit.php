<?php 

    session_start();

    require_once "../../connect.php";

    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $newsname = $_POST['newsname'];
        $newsdetail = $_POST['newsdetail'];
        $img = $_FILES['img'];
       

        $img2 = $_POST['img2'];
        $upload = $_FILES['img']['name'];

        if ($upload != '') {
            $allow = array('jpg', 'jpeg', 'png');
            $extension = explode('.', $img['name']);
            $fileActExt = strtolower(end($extension));
            $fileNew = rand() . "." . $fileActExt;
            $filePath = '/Images/'.$fileNew;
            $dirpath = realpath(dirname(getcwd()));

            if (in_array($fileActExt, $allow)) {
                if ($img['size'] > 0 && $img['error'] == 0) {
                   move_uploaded_file($img['tmp_name'], $dirpath.$filePath);
                }
            }

        } else {
            $fileNew = $img2;
        }

        $sql = $conn->prepare("UPDATE new SET newsname = :newsname, newsdetail = :newsdetail, img = :img WHERE id = :id");
        $sql->bindParam(":id", $id);
        $sql->bindParam(":newsname", $newsname);
        $sql->bindParam(":newsdetail", $newsdetail);
        $sql->bindParam(":img", $fileNew);
        $sql->execute();

        if ($sql) {
            $_SESSION['success'] = "Data has been updated successfully";
            header("location: news.php");
        } else {
            $_SESSION['error'] = "Data has not been updated successfully";
            header("location: news.php");
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <style>
        .container {
            max-width: 550px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Data</h1>
        <hr>
        <form action="edit.php" method="post" enctype="multipart/form-data">
            <?php
                if (isset($_GET['id'])) {
                        $id = $_GET['id'];
                        $stmt = $conn->query("SELECT * FROM new WHERE id = $id");
                        $stmt->execute();
                        $data = $stmt->fetch();
                }
            ?>
                <div class="mb-3">
                    <label for="id" class="col-form-label">ID:</label>
                    <input type="text" readonly value="<?php echo $data['id']; ?>"  name="id" required class="form-control">
                <div class="mb-3">
                    <label for="img" class="col-form-label">Image:</label>
                    <input type="file" class="form-control" id="imgInput" name="img">
                    <img width="100%" src="/Manu/Images/<?php echo $data['img']; ?>" id="previewImg" alt="">
                </div>
                    <label for="newsname" class="col-form-label">Name:</label>
                    <input type="text" value="<?php echo $data['newsname']; ?>" name="newsname" required class="form-control" >
                    <input type="hidden" value="<?php echo $data['img']; ?>"  name="img2" required class="form-control">
                </div>
                <div class="mb-3">
                    <label for="newsname" class="col-form-label">Detail:</label>
                    <input type="text" value="<?php echo $data['newsdetail']; ?>"  name="newsdetail" required class="form-control">
                </div>
                <hr>
                <a href="news.php" class="btn btn-secondary">Go Back</a>
                <button type="submit" name="update" class="btn btn-primary">Update</button>
            </form>
    </div>

    <script>
        let imgInput = document.getElementById('imgInput');
        let previewImg = document.getElementById('previewImg');

        imgInput.onchange = evt => {
            const [file] = imgInput.files;
                if (file) {
                    previewImg.src = URL.createObjectURL(file)
            }
        }

    </script>
</body>
</html>