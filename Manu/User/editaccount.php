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
       

        $sql = $conn->prepare("UPDATE member SET firstname = :firstname, lastname = :lastname, address = :address, tel = :tel, email = :email WHERE member_id = :member_id;");
        $sql->bindParam(":member_id", $member_id);
        $sql->bindParam(":firstname", $firstname);
        $sql->bindParam(":lastname", $lastname);
        $sql->bindParam(":address", $address);
        $sql->bindParam(":tel", $tel);
        $sql->bindParam(":email", $email);
        $sql->execute();

        if ($sql) {
            $_SESSION['success'] = "Data has been updated successfully";
            header("location: ../../user.php");
        } else {
            $_SESSION['error'] = "Data has not been updated ";
            header("location: editaccount.php");
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h1>Edit Account</h1>
            </div>
            <div class="col-md-4 d-flex justify-content-end">
                <a href="../../user.php" class="btn btn-secondary">Go Back</a>
            </div>
        </div>
        <hr>
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

        <form action="editaccount.php" method="post" enctype="multipart/form-data">
            <?php
            if (isset($_SESSION['user_login'])) {
                $id = $_SESSION['user_login'];
                $stmt = $conn->query("SELECT * FROM member WHERE member_id = $id");
                $stmt->execute();
                $data = $stmt->fetch();
            }
            ?>
            <div class="mb-3">
                <label for="firstname" class="col-form-label">First Name:</label>
                <input type="text" value="<?php echo $data['firstname']; ?>" name="firstname" required class="form-control">
            </div>
            <div class="mb-3">
                <label for="lastname" class="col-form-label">Last Name:</label>
                <input type="text" value="<?php echo $data['lastname']; ?>" name="lastname" required class="form-control">
            </div>
            <div class="mb-3">
                <label for="address" class="col-form-label">Address:</label>
                <input type="text" value="<?php echo $data['address']; ?>" name="address" required class="form-control">
            </div>
            <div class="mb-3">
                <label for="tel" class="col-form-label">Tel:</label>
                <input type="text" value="<?php echo $data['tel']; ?>" name="tel" required class="form-control">
            </div>
            <div class="mb-3">
                <label for="email" class="col-form-label">Email:</label>
                <input type="email" value="<?php echo $data['email']; ?>" name="email" required class="form-control">
            </div>

            <input type="hidden" value="<?php echo $data['member_id']; ?>" name="member_id" required class="form-control">
            <hr>
            <button type="submit" name="update" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>

</html>