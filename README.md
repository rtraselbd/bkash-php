# bKash PHP Library

The bKash PHP library is a convenient way to integrate the bKash payment gateway into your PHP applications. It provides methods for creating and verifying payments. With this library, you can offer your users a seamless and secure way to make payments using the bKash platform.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [License](#license)

## Installation

You can easily install the bKash PHP library via [Composer](https://getcomposer.org/):

```bash
composer require rtraselbd/bkash-php
```

## Usage

To get started with the bKash PHP library, follow these steps:

1. **Initialize the bKash Object**: Create an instance of the `bKash` class by providing your bKash API credentials in an array.

   ```php
    use RT\bKash\bKash;

    require 'vendor/autoload.php';

    $credential = [
       'username'      => 'your-bKash-username',
       'password'      => 'your-bKash-password',
       'app_key'       => 'your-app-key',
       'app_secret'    => 'your-app-secret',
    ];
    $bKash = new bKash($credential);
   ```

2. **Create a Payment Request**: Use the `createPayment` method to create a payment request. Provide the required payment request data, including the amount, success URL, and brand name.

   ```php
   $requestData = [
       'amount'        => 10,
       'success_url'   => 'https://your-website.com/success.php',
       'brand_name'    => 'YourBrandName',
   ];

   try {
       $bKashURL = $bKash->createPayment($requestData);
       // Redirect the user to the bKash payment URL
       header('Location: ' . $bKashURL);
   } catch (Exception $e) {
       // Handle any exceptions or errors here
       $error = $e->getMessage();
   }
   ```

3. **Verify a Payment**: To verify a payment, retrieve the `paymentID` from the query parameters and use the `verifyPayment` method.

   ```php
   $paymentID = $_GET['paymentID'];

   try {
       $response = $bKash->verifyPayment($paymentID);
   } catch (Exception $e) {
       // Handle any exceptions or errors here
       $error = $e->getMessage();
   }
   ```

## License

This library is open-source and licensed under the [MIT License](LICENSE). You are free to use and modify it in accordance with the terms of the license.

Feel free to contribute, report issues, or suggest improvements! Your feedback and contributions are highly appreciated.