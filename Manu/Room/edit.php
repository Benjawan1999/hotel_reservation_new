<?php 

    session_start();

    require_once "../../connect.php";

    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $roomtype = $_POST['roomtype'];
        $price= $_POST['price'];
        $detail = $_POST['detail'];
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

        $sql = $conn->prepare("UPDATE room SET roomtype_id = :roomtype_id, price = :price, detail = :detail, img = :img WHERE id = :id");
        $sql->bindParam(":id", $id);
        $sql->bindParam(":roomtype_id", $roomtype);
        $sql->bindParam(":price", $price);
        $sql->bindParam(":detail", $detail);
        $sql->bindParam(":img", $fileNew);
        $sql->execute();

        if ($sql) {
            $_SESSION['success'] = "Data has been updated successfully";
            header("location: room.php");
        } else {
            $_SESSION['error'] = "Data has not been updated successfully";
            header("location: room.php");
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
                        $stmt = $conn->query("SELECT * FROM room WHERE id = $id");
                        $stmt->execute();
                        $data = $stmt->fetch();
                }
            ?>
                <div class="mb-3">
                    <label for="id" class="col-form-label">ID:</label>
                    <input type="text" readonly value="<?php echo $data['id']; ?>" required class="form-control" name="id" >
                </div>
                <div class="mb-3">
                    <label for="img" class="col-form-label">Image:</label>
                    <input type="file" class="form-control" id="imgInput" name="img">
                    <img width="100%" src="/Manu/Images/<?php echo $data['img']; ?>" id="previewImg" alt="">
                </div>
                <div class="mb-3">
                            <label for="roomtype" class="col-form-label">Room Type:</label>
                            <select class="form-select" aria-label="Default select example" name="roomtype" value="<?php echo $data['roomtype_id']; ?>">
                                <option selected>Select Room Type</option>

                                <?php
                                $stmt = $conn->query("SELECT * FROM roomtype ");
                                $stmt->execute();
                                $roomtype = $stmt->fetchAll();

                                if (!$roomtype) {
                                    echo "<p><td colspan='6' class='text-center'>No data available</td></p>";
                                } else {
                                    foreach ($roomtype as $rt) {
                                        if ($rt['id'] == $data['roomtype_id']) {  ?>
                                            <option value="<?php echo $rt['id']; ?>" selected><?php echo $rt['name']; ?></option>
                                        <?php } else { ?>
                                    <option value="<?php echo $rt['id']; ?>"><?php echo $rt['name']; ?></option>
                                <?php } } } ?>

                            </select>
                            <input type="hidden" value="<?php echo $data['img']; ?>" required class="form-control" name="img2" >
                        </div>
                <div class="mb-3">
                    <label for="roomtype" class="col-form-label">Price:</label>
                    <input type="text" value="<?php echo $data['price']; ?>" required class="form-control" name="price">
                </div>
                <div class="mb-3">
                    <label for="roomtype" class="col-form-label">Detail:</label>
                    <input type="text" value="<?php echo $data['detail']; ?>" required class="form-control" name="detail">
                </div>
                <hr>
                <a href="room.php" class="btn btn-secondary">Go Back</a>
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