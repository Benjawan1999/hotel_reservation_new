<?php
session_start();
require_once "../connect.php";

if (isset($_GET['checkout'])) {
    $checkout_id = $_GET['checkout'];
    $room_id = $_GET['roomid'];
    $fine = $_GET['fine'];
    $checkoutstmt = $conn->query("UPDATE booking SET status = 3,fine=$fine WHERE id = $checkout_id");
    $checkoutstmt->execute();


    if ($checkoutstmt) {
        $roomstmt = $conn->query("UPDATE room SET status = 0 WHERE id = $room_id");
        $roomstmt->execute();

        echo "<script>alert('Data has been checkout successfully');</script>";
        $_SESSION['success'] = "Data has been checkout succesfully";
        header("refresh:1; url=checkoutbooking.php");
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
    <title>Check out booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h1>Check Out Booking</h1>
            </div>
            <div class="col-md-4 d-flex justify-content-end">
                <a href="../admin.php" class="btn btn-secondary">Go Back</a>
            </div>
        </div>
        <hr>

        <table id="example" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Contact Name</th>
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
                $stmt = $conn->query("SELECT b.*,rt.name,pk.name AS packagename, r.img, m.firstname, m.lastname, r.id As roomid
                FROM booking b
                INNER JOIN room r
                ON b.room_id = r.id
                INNER JOIN roomtype rt
                ON r.roomtype_id = rt.id
                LEFT OUTER JOIN package pk
                ON b.package_id = pk.id
                INNER JOIN member m
                ON b.member_id = m.member_id
                WHERE b.status = 2
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
                            <td><?php echo $booking['firstname'] . ' ' . $booking['lastname']; ?></td>
                            <td width="250px"><img class="rounded" width="100%" src="/Manu/Images/<?php echo $booking['img']; ?>" alt=""></td>
                            <td><?php echo $booking['guests']; ?></td>
                            <td><?php echo $booking['name']; ?></td>
                            <td><?php echo $booking['check_in']; ?></td>
                            <td><?php echo $booking['check_out']; ?></td>
                            <td><?php echo $booking['packagename']; ?></td>
                            <td><?php echo number_format($booking['price']); ?> ฿</td>
                            <td><?php echo checkstatus($booking['status']); ?></td>
                            <td>
                                <button type="button" onclick="fineCheckout();" class="btn btn-danger">Check Out</button>
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

        function fineCheckout() {
            let money = prompt("How much is the fine", "0");
            if (money != null) {
                window.location.href = "?checkout=<?php echo $booking['id']; ?>&roomid=<?php echo $booking['roomid'] ?>&fine="+money;
            } else {
                window.location.href = "?checkout=<?php echo $booking['id']; ?>&roomid=<?php echo $booking['roomid'] ?>&fine=0";
            }
        }
    </script>
</body>

</html>