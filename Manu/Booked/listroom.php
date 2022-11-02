<?php
session_start();
require_once "../../connect.php";

$datas;

if (isset($_POST['search'])) {
    $start = $_POST['start'];
    $stop = $_POST['stop'];
    $searchstmt = $conn->query("select * from room
    where id not in (
    select r.id
    from room r,booking b
    where r.id = b.room_id
    and (b.check_in >= '$start' and b.check_out <= '$stop')
    )");
    $searchstmt->execute();
    $datas = $searchstmt->fetchAll();
} else {
    $stmt = $conn->query("SELECT * FROM room WHERE status = 0 ");
    $stmt->execute();
    $datas = $stmt->fetchAll();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Room</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">

</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h1>List of Room</h1>
            </div>
            <div class="col-md-4 d-flex justify-content-end">
                <a href="../../user.php" class="btn btn-secondary">Go Back</a>
            </div>
        </div>
        <hr>
        <?php if (isset($_SESSION['success'])) { ?>
            <div class="alert alert-success">
                <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
            </div>
        <?php } ?>
        <?php if (isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger">
                <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php } ?>

        <form action="?search=1" method="post">
            <div class="mb-3">
                <label for="start" class="form-label">Begin date/Begin time:</label>
                <input type="date" id="start" class="form-control" name="start" aria-describedby="start" min="<?php echo date("Y-m-d"); ?>" required>
            </div>
            <div class="mb-3">
                <label for="stop" class="form-label">End date/End time:</label>
                <input type="date" id="stop" class="form-control" name="stop" aria-describedby="stop" min="<?php echo date("Y-m-d"); ?>" required>
            </div>
            <button type="submit" name="search" class="btn btn-primary">Search</button>
        </form>

        <hr>
        <table id="example" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Img</th>
                    <th scope="col">RoomType</th>
                    <th scope="col">Price</th>
                    <th scope="col">Detail</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php


                if (!$datas) {
                    echo "<p><td colspan='6' class='text-center'>No data available</td></p>";
                } else {
                    foreach ($datas as $data) {
                ?>
                        <tr>
                            <th scope="row"><?php echo $data['id']; ?></th>
                            <td width="250px"><img class="rounded" width="100%" src="/Manu/Images/<?php echo $data['img']; ?>" alt=""></td>
                            <td><?php echo $data['roomtype_id']; ?></td>
                            <td><?php echo $data['price']; ?> ฿</td>
                            <td><?php echo $data['detail']; ?></td>
                            <td>
                                <a href="addbooked.php?id=<?php echo $data['id']; ?>" class="btn btn-success">Book a Room</a>
                            </td>
                        </tr>
                <?php }
                } ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });

        let imgInput = document.getElementById('imgInput');
        let previewImg = document.getElementById('previewImg');

        imgInput.onchange = evt => {
            const [file] = imgInput.files;
            if (file) {
                previewImg.src = URL.createObjectURL(file)
            }
        }
    </script>
    </div>
</body>

</html>