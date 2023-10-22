<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['paymentID'])) {
    $paymentID = $_GET['paymentID'];

    try {
        $response = $bKash->verifyPayment($paymentID);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Verification Result</title>
    <!-- Include Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h1>Payment Verification Result</h1>
                <?php
                if (isset($error)) {
                    // Display an alert with the error message
                    echo '<div class="alert alert-danger">' . $error . '</div>';
                } elseif (isset($response)) {
                    // Display the payment details using a table
                    echo '<div class="alert alert-success">Payment Verified Successfully!</div>';
                    echo '<table class="table table-bordered">';
                    foreach ($response as $key => $value) {
                        echo '<tr><td>' . htmlspecialchars($key) . '</td><td>' . htmlspecialchars($value) . '</td></tr>';
                    }
                    echo '</table>';
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>