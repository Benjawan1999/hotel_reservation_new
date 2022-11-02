<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h1>Payment</h1>
            </div>
            <div class="col-md-4 d-flex justify-content-end">
                <a href="mybooking.php" class="btn btn-secondary">Go Back</a>
            </div>
        </div>
        <hr>

        <form action="insert.php" method="post" enctype="multipart/form-data">
            <input type="hidden" readonly value="<?php echo $_GET['id']; ?>" required class="form-control" name="id">
            <div class="mb-3">
                <label for="bank" class="form-label">Bank Name:</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="bank" value="kbank" id="flexRadioDefault1">
                    <label class="form-check-label" for="flexRadioDefault1">
                        <div>กสิกรไทย สาขา ชุมพร</div>
                        <div>ชื่อบัญชี โรงแรม 5 ดาว</div>
                        <div>หมายเลขบัญชี 111-1-11111-1</div>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="bank" value="scb" id="flexRadioDefault2" checked>
                    <label class="form-check-label" for="flexRadioDefault2">
                        <div>ไทยพาณิชย์ สาขา ชุมพร</div>
                        <div>ชื่อบัญชี โรงแรม 5 ดาว</div>
                        <div>หมายเลขบัญชี 222-2-22222-2</div>
                    </label>
                </div>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Amount:</label>
                <div class="input-group">
                    <input type="number" class="form-control" name="amount" required value="<?php echo $_GET['price'] ?>" readonly>
                    <span class="input-group-text">฿</span>
                </div>

            </div>
            <div class="mb-3">
                <label for="img" class="col-form-label">Transfer Proof:</label>
                <input type="file" required class="form-control" id="imgInput" name="img">
                <img loading="lazy" width="50%" id="previewImg" alt="">
            </div>

            <button type="submit" name="submit" class="btn btn-success">Confirm</button>
    </div>
    </form>

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