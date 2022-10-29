<?php
session_start();
require_once "../../connect.php";

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $deletestmt = $conn->query("DELETE FROM new WHERE id = $delete_id");
    $deletestmt->execute();

    if ($deletestmt) {
        echo "<script>alert('Data has been deleted successfully');</script>";
        $_SESSION['success'] = "Data has been deleted succesfully";
        header("refresh:1; url=news.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>

<body>

    <?php

    if (isset($_SESSION['user_login'])) {
        $user_id = $_SESSION['user_login'];
        $stmt = $conn->prepare("SELECT * FROM member WHERE member_id = :id");
        $stmt->bindValue(":id", $user_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    ?>
    <nav class="navbar navbar-dark bg-dark navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="../../user.php">Home</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="news.php" class="navbar-brand">News</a>
                    </li>
                    <li class="nav-item">
                        <a class="navbar-brand" href="../Booked/listroom.php">Booking</a>
                    </li>
                    <li class="nav-item">
                        <a href="mybooking.php" class="navbar-brand">My Booking</a>
                    </li>
                    <li class="nav-item">
                        <a href="editaccount.php" class="navbar-brand">Edit Account</a>
                    </li>
                </ul>

                <div class="d-flex">
                    <a class="navbar-brand ">Welcome, <?php echo $row['firstname'] . ' ' . $row['lastname'] ?></a>
                    <a href="../../logout.php" class="btn btn-danger">Logout</a>
                </div>

            </div>
        </div>
    </nav>

    <!-- <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../../Home/home.php">Home</a>
            <a href="news.php" class="navbar-brand">News</a>
            <a class="navbar-brand" href="../../signin.php">Login</a>
        </div>
    </nav> -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h1>News</h1>
            </div>
            <!-- <div class="col-md-6 d-flex justify-content-end">
                <a href="../../Home/home.php" class="btn btn-secondary">Go Back</a>
            </div> -->
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
                    <th scope="col">Img</th>
                    <th scope="col">Name</th>
                    <th scope="col">Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->query("SELECT * FROM new");
                $stmt->execute();
                $news = $stmt->fetchAll();

                if (!$news) {
                    echo "<p><td colspan='6' class='text-center'>No data available</td></p>";
                } else {
                    foreach ($news as $new) {
                ?>
                        <tr>
                            <th scope="row"><?php echo $new['id']; ?></th>
                            <td width="250px"><img class="rounded" width="100%" src="/Manu/Images/<?php echo $new['img']; ?>" alt=""></td>
                            <td><?php echo $new['newsname']; ?></td>
                            <td><?php echo $new['newsdetail']; ?></td>
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
</body>

</html>