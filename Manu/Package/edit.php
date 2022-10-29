<?php

session_start();

require_once "../../connect.php";

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $detail = $_POST['detail'];
    $days = $_POST['days'];
    $start = $_POST['start'];
    $stop = $_POST['stop'];
    $price = $_POST['price'];
    $date1 = date("Y-m-d H:i:s", strtotime($start));
    $date2 = date("Y-m-d H:i:s", strtotime($stop));
    $roomtype = $_POST['roomtype'];
    $status = $_POST['status'];


    $sql = $conn->prepare("UPDATE package SET name = :name, detail = :detail, days = :days, start = :start, stop = :stop, price = :price, roomtype_id = :roomtype_id, status = :status WHERE id = :id;");
    $sql->bindParam(":id", $id);
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
        $_SESSION['success'] = "Data has been updated successfully";
        header("location: package.php");
    } else {
        $_SESSION['error'] = "Data has not been updated successfully";
        header("location: package.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Package</title>
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
                $stmt = $conn->query("SELECT *, DATE_FORMAT(start, '%Y-%m-%d') as date1, DATE_FORMAT(stop, '%Y-%m-%d') as date2 FROM package WHERE id = $id");
                $stmt->execute();
                $data = $stmt->fetch();
            }
            ?>
            <div class="mb-3">
                <label for="id" class="col-form-label">ID:</label>
                <input type="text" readonly value="<?php echo $data['id']; ?>" name="id" required class="form-control">
            </div>
            <div class="mb-3">
                <label for="name" class="col-form-label">Name:</label>
                <input type="text" value="<?php echo $data['name']; ?>" name="name" required class="form-control">
            </div>
            <div class="mb-3">
                <label for="detail" class="col-form-label">Detail:</label>
                <input type="text" value="<?php echo $data['detail']; ?>" name="detail" required class="form-control">
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
                    <?php }
                        }
                    } ?>

                </select>
            </div>
            <div class="mb-3">
                <label for="days" class="col-form-label">Days:</label>
                <input type="text" value="<?php echo $data['days']; ?>" name="days" required class="form-control">
            </div>
            <div class="mb-3">
                <label for="start" class="col-form-label">Start:</label>
                <input type="date" value="<?php echo $data['date1']; ?>" name="start" required class="form-control" min="<?php echo date("Y-m-d"); ?>">
            </div>
            <div class="mb-3">
                <label for="stop" class="col-form-label">Stop:</label>
                <input type="date" value="<?php echo $data['date2']; ?>" name="stop" required class="form-control" min="<?php echo date("Y-m-d"); ?>">
            </div>
            <div class="mb-3">
                <label for="price" class="col-form-label">Price:</label>
                <input type="number" value="<?php echo $data['price']; ?>" name="price" required class="form-control">
            </div>
            <div class="mb-3">
                <label for="status" class="col-form-label">Status:</label>
                <select class="form-select" aria-label="Default select example" name="status">
                    <?php if ($data['status'] == 1) { ?>
                        <option selected value="1">Enable</option>
                        <option value="0">Disable</option>
                    <?php } else { ?>
                        <option value="1">Enable</option>
                        <option value="0" selected>Disable</option>
                    <?php } ?>
                </select>
            </div>

            <hr>
            <a href="package.php" class="btn btn-secondary">Go Back</a>
            <button type="submit" name="update" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>

</html>