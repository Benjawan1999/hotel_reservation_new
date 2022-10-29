<?php 

    session_start();

    require_once "../../connect.php";

    if (isset($_POST['update'])) {
        $member_id = $_POST['member_id'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $address = $_POST['address'];
        $tel = $_POST['tel'];
        $email = $_POST['email'];
       

        $sql = $conn->prepare("UPDATE member SET firstname = :firstname, lastname = :lastname, addressaddress = :address, tel = :tel, email = :email WHERE member_id = :member_id;");
        $sql->bindParam(":member_id", $member_id);
        $sql->bindParam(":firstname", $firstname);
        $sql->bindParam(":lastname", $lastname);
        $sql->bindParam(":address", $address);
        $sql->bindParam(":tel", $tel);
        $sql->bindParam(":email", $email);
        $sql->execute();

        if ($sql) {
            $_SESSION['success'] = "Data has been updated successfully";
            header("location: member.php");
        } else {
            $_SESSION['error'] = "Data has not been updated successfully";
            header("location: member.php");
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Member</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <style>
        .container {
            max-width: 550px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Data</h1>
        <hr>
        <form action="edit.php" method="post" enctype="multipart/form-data">
            <?php
                if (isset($_GET['id'])) {
                        $id = $_GET['id'];
                        $stmt = $conn->query("SELECT * FROM member WHERE member_id = $id");
                        $stmt->execute();
                        $data = $stmt->fetch();
                }
            ?>
                <div class="mb-3">
                    <label for="member_id" class="col-form-label">ID:</label>
                    <input type="text" readonly value="<?php echo $data['member_id']; ?>"  name="member_id" required class="form-control">
                </div>
                <div class="mb-3">
                    <label for="firstname" class="col-form-label">First Name:</label>
                    <input type="text" value="<?php echo $data['firstname']; ?>" name="firstname" required class="form-control" >
                </div>
                <div class="mb-3">
                    <label for="lastname" class="col-form-label">Last Name:</label>
                    <input type="text" value="<?php echo $data['lastname']; ?>"  name="lastname" required class="form-control">
                </div>
                <div class="mb-3">
                    <label for="address" class="col-form-label">Address:</label>
                    <input type="text" value="<?php echo $data['address']; ?>"  name="address" required class="form-control">
                </div>
                <div class="mb-3">
                    <label for="tel" class="col-form-label">Tel:</label>
                    <input type="text" value="<?php echo $data['tel']; ?>"  name="tel" required class="form-control">
                </div>
                <div class="mb-3">
                    <label for="email" class="col-form-label">Email:</label>
                    <input type="email" value="<?php echo $data['email']; ?>"  name="email" required class="form-control">
                </div>
                
                <hr>
                <a href="member.php" class="btn btn-secondary">Go Back</a>
                <button type="submit" name="update" class="btn btn-primary">Update</button>
            </form>
    </div>
</body>
</html>