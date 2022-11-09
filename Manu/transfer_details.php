<?php 
    require_once "../connect.php";

    $id = $_GET['id'];
    $stmt = $conn->query("SELECT * FROM payment WHERE pay_id = $id");
    $stmt->execute();
    $payment = $stmt->fetch();

    


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between">
            <div class="">
                <h1 class="text-center">Transfer Details</h1>
            </div>
            <div class="">
                <a href="report.php" class="btn btn-secondary">Go Back</a>
            </div>
        </div>
        <hr>
        <div class="card mb-3 mx-auto" style="max-width: 540px;">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="Images/<?php echo $payment['img']; ?>" class="img-fluid rounded-start" alt="slip">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">Transfer Date: <?php echo $payment['pay_date']; ?></h5>
                        <h5 class="card-title">Bank: <?php echo $payment['bank']; ?></h5>
                        <h5 class="card-title">Amount: <?php echo number_format($payment['pay_total']); ?> THB</h5>
                        <!-- <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p> -->
                        <!-- <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p> -->
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
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
</body>

</html>