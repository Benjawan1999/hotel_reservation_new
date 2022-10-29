<?php

session_start();
require_once "../../connect.php";

if (isset($_POST['submit'])) {
    $start = $_POST['start'];
    $stop = $_POST['stop'];
    $package = $_POST['package'];
    $guests = $_POST['guests'];
    $room = $_POST['room'];
    $member = $_POST['member'];
    $price = $_POST['price'];
    $checkin = date("Y-m-d H:i:s", strtotime($start));
    $checkout = date("Y-m-d H:i:s", strtotime($stop));



    if ($package != 0) {
        $arrDate1 = explode("-", $start);
        $arrDate2 = explode("-", $stop);
        $timStmp1 = mktime(0, 0, 0, $arrDate1[2], $arrDate1[1], $arrDate1[0]);
        $timStmp2 = mktime(0, 0, 0, $arrDate2[2], $arrDate2[1], $arrDate2[0]);

        $sql = "SELECT *, DATE_FORMAT(start, '%Y-%m-%d') as date1, DATE_FORMAT(stop, '%Y-%m-%d') as date2 FROM package WHERE id = $package";
        $stmt = $conn->query($sql);
        $stmt->execute();
        $data = $stmt->fetch();
        $arrDate3 = explode("-", $data['date1']);
        $arrDate4 = explode("-", $data['date2']);
        $timStmp3 = mktime(0, 0, 0, $arrDate3[2], $arrDate3[1], $arrDate3[0]);
        $timStmp4 = mktime(0, 0, 0, $arrDate4[2], $arrDate4[1], $arrDate4[0]);
    }


    function booking($conn, $checkin, $checkout, $package, $guests, $room, $member, $price)
    {
        try {
            if (!isset($_SESSION['error'])) {
                $sql = $conn->prepare("INSERT INTO booking(check_in, check_out, package_id, guests, room_id, member_id, price) VALUES(:check_in, :check_out, :package_id, :guests, :room_id, :member_id, :price);");
                $sql->bindParam(":check_in", $checkin);
                $sql->bindParam(":check_out", $checkout);
                $sql->bindParam(":package_id", $package);
                $sql->bindParam(":guests", $guests);
                $sql->bindParam(":room_id", $room);
                $sql->bindParam(":member_id", $member);
                $sql->bindParam(":price", $price);

                $sql->execute();

                if ($sql) {

                    $sql2 = $conn->prepare("UPDATE room SET status = 1 WHERE id = :id;");
                    $sql2->bindParam(":id", $room);
                    $sql2->execute();

                    $_SESSION['success'] = "Data has been inserted successfully";
                    header("location: /Manu/User/mybooking.php");
                } else {
                    $_SESSION['error'] = "Data has not been inserted successfully";
                    header("location: addbooked.php?id=" . $room);
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }



    if ($timStmp1 >= $timStmp3 && $timStmp2 <= $timStmp4) {
        booking($conn, $checkin, $checkout, $package, $guests, $room, $member, $price);
    } elseif ($package == 0) {
        booking($conn, $checkin, $checkout, $package, $guests, $room, $member, $price);
    } else {
        $_SESSION['error'] = "package not use";
        header("location: addbooked.php?id=" . $room);
    }
}
