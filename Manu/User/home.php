<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="../Decorate/user.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
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
                        <a href="logout.php" class="navbar-brand">Logout</a>
                    </li>
            </div>
        </div>
    </nav>

    <?php
    $thai_month_arr = array(
        "1" => "มกราคม",
        "2" => "กุมภาพันธ์",
        "3" => "มีนาคม",
        "4" => "เมษายน",
        "5" => "พฤษภาคม",
        "6" => "มิถุนายน",
        "7" => "กรกฎาคม",
        "8" => "สิงหาคม",
        "9" => "กันยายน",
        "10" => "ตุลาคม",
        "11" => "พฤศจิกายน",
        "12" => "ธันวาคม"
    );
    //$current_month = "2008-11-01";
    $current_month = date("Y-m-01"); // เดือนปัจจุบัน หากต้องการเป็นเดือนอื่นก็เปลี่ยน เช่น Y-02-01
    $mk_time = strtotime($current_month); // สร้างรูปแบบวันที่มาตรฐาน Timestamp
    $current_date = date("j"); // วันที่ปัจจุบัน 
    $total_day_in_month = date("t", $mk_time); // จำนวนวันในเดือนนี้
    $first_day_in_month = date("w", $mk_time); // หาวันที่ 1 ตรงกับวันอะไร วันอาทิตย์เท่ากับ 0
    $total_list_day = $total_day_in_month + $first_day_in_month; // จำนวนรายการวันที่ี่
    $rows_week = ceil($total_list_day / 7); //  คำนวนหาจำนวนสัปดาห์
    $total_box = $rows_week * 7; // จำนวนรายการวันที่ทั้งหมด

    // ฟังก์ชั่นสร้างวันที่ กำหนด เริ่มต้น (เริ่มต้น 1, วันที่ 1 ตรงกัลวันที่ , วันที่ปัจจุบัน, จำนวนวันวในเดือนนั้นๆ)
    function drawDate($no_day, $first_day_in_month, $current_date, $total_day_in_month)
    {
        $wan_tee = $no_day - $first_day_in_month;
        if ($wan_tee <= 0) {
            return '<li class="no-date"></li>';
        } else {
            if ($wan_tee <= $total_day_in_month) {
                if ($wan_tee == $current_date) {
                    return '<li class="current">' . $wan_tee . '</li>';
                } else {
                    return '<li>' . $wan_tee . '</li>';
                }
            } else {
                return "";
            }
        }
    }
    ?>
    <h1 class="text-center">Calendar</h1>

    <div class="wrap-calendar-lev-1 m-auto">
        <div class="wrap-month-year">
            <?= $thai_month_arr[intval(date("m"))] ?> <?= date("Y") + 543 ?>
        </div>
        <ul class="wrap-dayname-list">
            <li>อา</li>
            <li>จ</li>
            <li>อ</li>
            <li>พ</li>
            <li>พฤ</li>
            <li>ศ</li>
            <li>ส</li>
        </ul>
        <ul class="wrap-day-list">
            <?php
            for ($i = 1; $i <= $total_box; $i++) { // วนลูปสร้างวันที่
                echo drawDate($i, $first_day_in_month, $current_date, $total_day_in_month);
            }
            ?>
        </ul>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>