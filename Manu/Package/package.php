<?php
session_start();
require_once "../../connect.php";

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $deletestmt = $conn->query("DELETE FROM package WHERE id = $delete_id");
    $deletestmt->execute();

    if ($deletestmt) {
        echo "<script>alert('Data has been deleted successfully');</script>";
        $_SESSION['success'] = "Data has been deleted succesfully";
        header("refresh:1; url=package.php");
    }
}

function checkstatus($status)
{
    if ($status == 0) {
        return "ปิดการใช้งาน";
    } elseif ($status == 1) {
        return "เปิดการใช้งาน";
    } else {
        return "เกิดข้อผิดพลาด";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>package</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>

<body>
    <div class="modal fade" id="packageModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Package</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="insert.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="col-form-label">Package Name:</label>
                            <input type="text" required class="form-control" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="roomtype" class="col-form-label">Room Type:</label>
                            <select class="form-select" aria-label="Default select example" name="roomtype">
                                <option selected>Select Room Type</option>

                                <?php
                                $stmt = $conn->query("SELECT * FROM roomtype ");
                                $stmt->execute();
                                $roomtype = $stmt->fetchAll();

                                if (!$roomtype) {
                                    echo "<p><td colspan='6' class='text-center'>No data available</td></p>";
                                } else {
                                    foreach ($roomtype as $rt) {
                                ?>

                                        <option value="<?php echo $rt['id']; ?>"><?php echo $rt['name']; ?></option>

                                <?php }
                                } ?>

                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="detail" class="col-form-label">Detail:</label>
                            <input type="text" required class="form-control" name="detail">
                        </div>
                        <div class="mb-3">
                            <label for="days" class="col-form-label">Days:</label>
                            <input type="text" required class="form-control" name="days">
                        </div>
                        <div class="mb-3">
                            <label for="start" class="col-form-label">Start:</label>
                            <input type="date" required class="form-control" name="start" min="<?php echo date("Y-m-d"); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="stop" class="col-form-label">Stop:</label>
                            <input type="date" required class="form-control" name="stop" min="<?php echo date("Y-m-d"); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="price" class="col-form-label">Price:</label>
                            <input type="number" required class="form-control" name="price">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="col-form-label">Status:</label>
                            <select class="form-select" aria-label="Default select example" name="status">
                                <option selected value="1">Enable</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h1>Package</h1>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#packageModal" data-bs-whatever="@mdo">Add Package</button>
                <a href="../../admin.php" class="btn btn-secondary">Go Back</a>
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
        <table id="example" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Package Name</th>
                    <th scope="col">Detail</th>
                    <th scope="col">Room Type</th>
                    <th scope="col">Days</th>
                    <th scope="col">Start</th>
                    <th scope="col">Stop</th>
                    <th scope="col">Price</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->query("SELECT pk.*,rt.name AS roomtypename 
                FROM package pk
                INNER JOIN roomtype rt 
                ON pk.roomtype_id = rt.id");
                $stmt->execute();
                $packages = $stmt->fetchAll();

                if (!$packages) {
                    echo "<p><td colspan='6' class='text-center'>No data available</td></p>";
                } else {
                    foreach ($packages as $package) {
                ?>
                        <tr>
                            <th scope="row"><?php echo $package['id']; ?></th>
                            <td><?php echo $package['name']; ?></td>
                            <td><?php echo $package['detail']; ?></td>
                            <td><?php echo $package['roomtypename']; ?></td>
                            <td><?php echo $package['days']; ?></td>
                            <td><?php echo $package['start']; ?></td>
                            <td><?php echo $package['stop']; ?></td>
                            <td><?php echo $package['price']; ?></td>
                            <td><?php echo checkstatus($package['status']); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $package['id']; ?>" class="btn btn-warning">Edit</a>
                                <a onclick="return confirm('Are you sure you want to delete?');" href="?delete=<?php echo $package['id']; ?>" class="btn btn-danger">Delete</a>
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
    </script>
</body>

</html>