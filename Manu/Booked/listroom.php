<?php 
    session_start();
    require_once "../../connect.php";

    if (isset($_GET['delete'])) {
        $delete_id = $_GET['delete'];
        $deletestmt = $conn->query("DELETE FROM room WHERE id = $delete_id");
        $deletestmt->execute();

        if ($deletestmt) {
            echo "<script>alert('Data has been deleted successfully');</script>";
            $_SESSION['success'] = "Data has been deleted succesfully";
            header("refresh:1; url=room.php");
        }
        
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet" >
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet" >

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
                        $stmt = $conn->query("SELECT * FROM room WHERE status = 0 ");
                        $stmt->execute();
                        $rooms = $stmt->fetchAll();

                        if (!$rooms) {
                            echo "<p><td colspan='6' class='text-center'>No data available</td></p>";
                        } else {
                        foreach($rooms as $room)  {  
                    ?>
                        <tr>
                            <th scope="row"><?php echo $room['id']; ?></th>
                            <td width="250px"><img class="rounded" width="100%" src="/Manu/Images/<?php echo $room['img']; ?>" alt=""></td>
                            <td><?php echo $room['roomtype_id']; ?></td>
                            <td><?php echo $room['price']; ?></td>
                            <td><?php echo $room['detail']; ?></td>
                            <td>
                                <a href="addbooked.php?id=<?php echo $room['id']; ?>" class="btn btn-success">Book a Room</a>
                            </td>
                        </tr>
                    <?php }  } ?>
                </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
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