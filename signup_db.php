<?php

    session_start();
    require_once 'connect.php';

    if (isset($_POST['signup'])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $address = $_POST['address'];
        $tel = $_POST['tel'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $c_password = $_POST['c_password'];
        $urole = 'user';

        if (empty($firstname)) {
            $_SESSION['error'] = 'กรุณากรอกชื่อ';
            header("location: index.php");
        } else if (empty($lastname)) {
            $_SESSION['error'] = 'กรุณากรอกนามสกุล';
            header("location: index.php");
        } else if (empty($address)) {
            $_SESSION['error'] = 'กรุณากรอกที่อยู่';
            header("location: index.php");
        } else if (empty($tel)) {
            $_SESSION['error'] = 'กรุณากรอกเบอร์โทร';
            header("location: index.php");
        } else if (empty($email)) {
            $_SESSION['error'] = 'กรุณากรอกอีเมล';
            header("location: index.php");
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'รูปแบบอีเมลไม่ถูกต้อง';
            header("location: index.php");
        } else if (empty($password)) {
            $_SESSION['error'] = 'กรุณากรอกพาสเวิร์ด';
            header("location: index.php");
        } else if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
            $_SESSION['error'] = 'รหัสผ่านต้องมีความยาวระหว่าง 5 ถึง 20 ตัวอักษร';
            header("location: index.php");
        } else if (empty($c_password)) {
            $_SESSION['error'] = 'กรุณายืนยันรหัสผ่าน';
            header("location: index.php");
        } else if ($password != $c_password) {
            $_SESSION['error'] = 'รหัสผ่านไม่ตรงกัน';
            header("location: index.php");
        } else {
            try {

                $check_data = $conn->prepare("SELECT email FROM member WHERE email = :email");
                $check_data->bindValue(":email", $email);
                $check_data->execute();
                $row = $check_data->fetch(PDO::FETCH_ASSOC);
            

                if (isset($row['email']) && $row['email'] == $email) {
                    $_SESSION['warning'] = "มีอีเมลนี้อยู่ในระบบแล้ว <a href='signin.php'>คลิ๊กที่นี้เพื่อเข้าสู่ระบบ</a>";
                    header("location: index.php");
                } else if (!isset($_SESSION['error'])){
                    
                    $stmt = $conn->prepare("INSERT INTO member(firstname, lastname, address, tel, email, password, urole)
                                            VALUES(:firstname, :lastname, :address, :tel, :email, :password, :urole)");     
                    $stmt->bindValue(":firstname",$firstname);  
                    $stmt->bindValue(":lastname",$lastname);  
                    $stmt->bindValue(":address",$address);  
                    $stmt->bindValue(":tel",$tel);  
                    $stmt->bindValue(":email",$email);     
                    $stmt->bindValue(":password",$password); 
                    $stmt->bindValue(":urole",$urole);  
                    $stmt->execute();        
                    $_SESSION['success'] = "สมัครสมาชิกเรียบร้อยแล้ว! <a href='signin.php' class='alert-link'>คลิ๊กที่นี่เพื่อเข้าสู่ระบบ</a>" ;
                    header("location: index.php");       
                } else {
                    $_SESSION['error'] = "มีบางอย่างผิดพลาด";
                    header("location: index.php");  
                }

                

            } catch(PDOException $e) {
                echo $e->getMessage();
                
            }
        }
    }

?>