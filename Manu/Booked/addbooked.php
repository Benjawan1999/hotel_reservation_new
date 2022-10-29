<?php
session_start();
require_once "../../connect.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Booked</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1>Details of Booked</h1>
            </div>
            <div class="col-md-4 d-flex justify-content-end">
                <a href="listroom.php" class="btn btn-secondary">Go Back</a>
            </div>
        </div>
        <hr>
        <form action="addbooked_db.php" method="post">
            <?php
            if (isset($_SESSION['user_login'])) {
                $id = $_SESSION['user_login'];
                $stmt = $conn->query("SELECT *  FROM member WHERE member_id = $id");
                $stmt->execute();
                $data = $stmt->fetch();
            }
            ?>
            <?php
            if (isset($_GET['id'])) {
                $roomid = $_GET['id'];
                $roomstmt = $conn->query("SELECT *  FROM room WHERE id = $roomid");
                $roomstmt->execute();
                $roomdata = $roomstmt->fetch();

                if ($roomdata['roomtype_id']) {
                    $roomtypeid = $roomdata['roomtype_id'];
                    $roomtypestmt = $conn->query("SELECT *  FROM roomtype WHERE id = $roomtypeid");
                    $roomtypestmt->execute();
                    $roomtypedata = $roomtypestmt->fetch();
                }
            }

            ?>
            <?php if (isset($_SESSION['error'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php } ?>
            <?php if (isset($_SESSION['success'])) { ?>
                <div class="alert alert-success" role="alert">
                    <?php
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php } ?>
            <?php if (isset($_SESSION['warning'])) { ?>
                <div class="alert alert-warning" role="alert">
                    <?php
                    echo $_SESSION['warning'];
                    unset($_SESSION['warning']);
                    ?>
                </div>
            <?php } ?>
            <div class="mb-3">
                <label for="roomtype" class="form-label">Room Type:</label>
                <input type="text" class="form-control" name="roomtype" aria-describedby="roomtype" disabled value="<?php echo $roomtypedata['name']; ?>">
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Contact name:</label>
                <input type="text" class="form-control" name="name" aria-describedby="name" disabled value="<?php echo $data['firstname']; ?> <?php echo $data['lastname']; ?>">
            </div>
            <div class="mb-3">
                <label for="tel" class="form-label">Tel:</label>
                <input type="text" class="form-control" name="tel" aria-describedby="tel" disabled value="<?php echo $data['tel']; ?>">
            </div>
            <hr>
            <div class="mb-3">
                <label for="guests" class="form-label">Guests:</label>
                <input type="number" class="form-control" name="guests" required>
            </div>
            <div class="mb-3">
                <label for="start" class="form-label">Begin date/Begin time:</label>
                <input type="date" id="start" class="form-control" name="start" aria-describedby="start" min="<?php echo date("Y-m-d"); ?>" required>
            </div>
            <div class="mb-3">
                <label for="stop" class="form-label">End date/End time:</label>
                <input type="date" id="stop" class="form-control" name="stop" aria-describedby="stop" min="<?php echo date("Y-m-d"); ?>" required>
            </div>
            <div class="mb-3">
                <label for="package" class="col-form-label">Package:</label>
                <select class="form-select" aria-label="Default select example" name="package" id="pack">
                    <option data-pack="0" selected="selected" value="0">Select Package</option>

                    <?php
                    $stmt = $conn->query("SELECT * FROM package WHERE status = 1 ");
                    $stmt->execute();
                    $package = $stmt->fetchAll();

                    if (!$package) {
                        echo "<p><td colspan='6' class='text-center'>No data available</td></p>";
                    } else {
                        foreach ($package as $pk) {
                    ?>

                            <option data-pack="<?php echo $pk['price'] ?>" value="<?php echo $pk['id']; ?>"><?php echo $pk['name']; ?> | start: <?php echo $pk['start']; ?> | stop: <?php echo $pk['stop']; ?></option>

                    <?php }
                    } ?>

                </select>
                <input type="hidden" value="<?php echo $roomid; ?>" name="room" required class="form-control">
                <input type="hidden" value="<?php echo $id; ?>" name="member" required class="form-control">
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price:</label>
                <input type="number" id="price" class="form-control" name="price" required readonly value="<?php echo $roomdata['price'] ?>">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Booking</button>
        </form>
    </div>
    <script>
        const start = document.getElementById('start');
        const stop = document.getElementById('stop');
        const price = document.getElementById('price');
        const pack = document.getElementById('pack');

        start.addEventListener('change', updateValue);
        stop.addEventListener('change', updateValue);
        pack.addEventListener('change', updatepackage);

        function updatepackage(e){
            const datapack = e.target.options[event.target.selectedIndex].dataset.pack;
            const priceupdate = price.value-datapack;
            price.value = priceupdate;
        }

        function updateValue(e) {
            if (start.value != "" && stop.value != "") {
                const number = dateDiff(start.value,stop.value);
                const price1 = price.value * number;
                price.value = price1;
            }
        }

        function dateDiff(start,stop) {
            const start1 = start.split("-");
            const stop1 = stop.split("-");
            const date1 = new Date(start1[1]+"/"+start1[2]+"/"+start1[0]);
            const date2 = new Date(stop1[1]+"/"+stop1[2]+"/"+stop1[0]);
            const diffTime = Math.abs(date2 - date1);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            return diffDays;
        }
    </script>

</body>

</html>