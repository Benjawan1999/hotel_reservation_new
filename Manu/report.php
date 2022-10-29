<?php 
    require_once "../connect.php";

    if (isset($_GET['confirm'])) {
        $confirm_id = $_GET['confirm'];
        $confirmstmt = $conn->query("UPDATE booking
        SET status = 2
        WHERE id = $confirm_id;");
        $confirmstmt->execute();
    
        if ($confirmstmt) {
            echo "<script>alert('Data has been confirm successfully');</script>";
            $_SESSION['success'] = "Data has been confirm succesfully";
            header("refresh:1; url=report.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h1>Report</h1>
            </div>
            <div class="col-md-4 d-flex justify-content-end">
                <a href="../admin.php" class="btn btn-secondary">Go Back</a>
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

            <table id="example" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Booking Date</th>
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
                    $stmt = $conn->query("SELECT DISTINCT b.id,pm.pay_date,b.guests,rt.name,
                    b.check_in, b.check_out,pk.name as pk,b.price,b.status 
                    FROM booking b 
                    INNER JOIN payment pm 
                    ON b.id = pm.booking_id 
                    INNER JOIN room r 
                    ON r.id = b.room_id 
                    INNER JOIN roomtype rt
                    ON rt.id = r.roomtype_id
                    LEFT OUTER JOIN package pk
                    ON pk.id = b.package_id
                    WHERE b.status = 1");
                    $stmt->execute();
                    $reports = $stmt->fetchAll();

                    if (!$reports) {
                        echo "<p><td colspan='6' class='text-center'>No data available</td></p>";
                    } else {
                        foreach ($reports as $report) {
                    ?>
                            <tr>
                                <th scope="row"><?php echo $report['id']; ?></th>
                                <td><?php echo $report['pay_date'] ?></td>
                                <td><?php echo $report['guests'] ?></td>
                                <td><?php echo $report['name'] ?></td>
                                <td><?php echo $report['check_in'] ?></td>
                                <td><?php echo $report['check_out'] ?></td>
                                <td><?php echo $report['pk'] ?></td>
                                <td><?php echo $report['price'] ?></td>
                                <td><?php echo $report['status'] ?></td>

                                <td>
                                    <a onclick="return confirm('Are you sure you want to confirm?');" href="?confirm=<?php echo $report['id']; ?>" class="btn btn-info">Confirm</a>
                                </td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
        <hr>
    </div>
</body>

</html>