<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'], $_POST['amount'])) {
        $requestData = [
            'amount'                => $_POST['amount'],
            'payer_reference'       => '', //Any related reference value which can be passed along with the payment request
            'invoice_number'        => '', //Unique invoice number used at merchant side for this specific payment.
            'success_url'           => $success_url,
            'brand_name'            => $brand_name
        ];

        try {
            $bKashURL = $bKash->createPayment($requestData);
            header('Location: ' . $bKashURL);
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bKash Payment Form</title>
    <!-- Include Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h1>Payment Form</h1>
                <?php
                if (isset($error)) {
                    echo '<div class="alert alert-danger">' . $error . '</div>';
                }
                ?>
                <form method="POST" action="index.php">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="text" class="form-control" name="amount" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>