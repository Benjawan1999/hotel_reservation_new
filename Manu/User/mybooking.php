<?php
session_start();
require_once "../../connect.php";
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $deletestmt = $conn->query("DELETE FROM booking WHERE id = $delete_id");
    $deletestmt->execute();

    if ($deletestmt) {
        echo "<script>alert('Data has been cancel successfully');</script>";
        $_SESSION['success'] = "Data has been cancel succesfully";
        header("refresh:1; url=mybooking.php");
    }
}

function checkstatus($status)
{
    if ($status == 0) {
        return "รอชำระเงิน";
    } elseif ($status == 1) {
        return "รอการตรวจสอบ";
    } elseif ($status == 2) {
        return "ชำระเงินเรียบร้อย";
    } elseif ($status == 3) {
        return "check out";
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
    <title>My Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h1>My Booking</h1>
            </div>
            <div class="col-md-4 d-flex justify-content-end">
                <a href="../../user.php" class="btn btn-secondary">Go Back</a>
            </div>
        </div>
        <hr>


        <table id="example" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Image</th>
                    <th scope="col">Guests</th>
                    <th scope="col">Room</th>
                    <th scope="col">check_in</th>
                    <th scope="col">check_out</th>
                    <th scope="col">Package</th>
                    <th scope="col">Price</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $id = $_SESSION['user_login'];
                $stmt = $conn->query("SELECT b.*,rt.name,pk.name AS packagename, r.img
                FROM booking b
                INNER JOIN room r
                ON b.room_id = r.id
                INNER JOIN roomtype rt
                ON r.roomtype_id = rt.id
                LEFT OUTER JOIN package pk
                ON b.package_id = pk.id
                WHERE b.member_id = $id 
                ORDER BY b.id DESC;");
                $stmt->execute();
                $bookings = $stmt->fetchAll();

                if (!$bookings) {
                    echo "<p><td colspan='6' class='text-center'>No data available</td></p>";
                } else {
                    foreach ($bookings as $booking) {
                ?>
                        <tr>
                            <th></th>
                            <td width="250px"><img class="rounded" width="100%" src="/Manu/Images/<?php echo $booking['img']; ?>" alt=""></td>
                            <td><?php echo $booking['guests']; ?></td>
                            <td><?php echo $booking['name']; ?></td>
                            <td><?php echo $booking['check_in']; ?></td>
                            <td><?php echo $booking['check_out']; ?></td>
                            <td><?php echo $booking['packagename']; ?></td>
                            <td><?php echo $booking['price']; ?></td>
                            <td><?php echo checkstatus($booking['status']); ?></td>

                            <td>
                                <?php if ($booking['status'] == 0) { ?>
                                    <a href="payment.php?id=<?php echo $booking['id']; ?>" class="btn btn-success">Payment</a>
                                    <a onclick="return confirm('Are you sure you want to cancel?');" href="?delete=<?php echo $booking['id']; ?>" class="btn btn-danger">Cancel</a>
                                <?php } ?>
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