# GloBee Payment API
[![Build Status](https://travis-ci.org/GloBee-Official/payment-api-php.svg?branch=master)](https://travis-ci.org/GloBee-Official/payment-api-php)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/GloBee-Official/payment-api-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/GloBee-Official/payment-api-php/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/GloBee-Official/payment-api-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/GloBee-Official/payment-api-php/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/globee/payment-api.svg)](https://packagist.org/packages/globee/payment-api)

This is a library to integrate your system with the GloBee Payment API
to manage and accept crypto payments through our system.

####Note:
If using PHP 7.0 or below, use version 0.4.0 or lower.

## Installation with Composer
Run the following command in your project to add this package:
```bash
composer require globee/payment-api
```

## Authenticate with GloBee
To receive a valid X-AUTH-ID, complete the following steps:
1) Sign into GloBee, and navigate to the Payment API section on the backend panel.
2) Copy the "Payment API Key" and store it somewhere in your code.

## Usage Example
To create an invoice on GloBee and receive a redirect to a payment interstitial, you can copy and modify the below code.
### Create new Payment Request
```php
<?php

include 'vendor/autoload.php';

$connector = new \GloBee\PaymentApi\Connectors\GloBeeCurlConnector('YOUR_UNIQUE_API_KEY');
$paymentApi = new \GloBee\PaymentApi\PaymentApi($connector);

$paymentRequest = new \GloBee\PaymentApi\Models\PaymentRequest(123.45, 'example@email.com');

$response = $paymentApi->createPaymentRequest($paymentRequest);

$paymentRequestId = $response->id; // Save this ID to know when payment has been made
$redirectUrl = $response->redirectUrl; // Redirect your client to this URL to make payment
```

### Fetch existing Payment Request
```php
<?php

include 'vendor/autoload.php';

$connector = new \GloBee\PaymentApi\Connectors\GloBeeCurlConnector(
    'YOUR_UNIQUE_API_KEY'
);
$paymentApi = new \GloBee\PaymentApi\PaymentApi($connector);

$response = $paymentApi->getPaymentRequest($paymentRequestId);
```

### Convert response from IPN into a PaymentRequest object
```php
<?php

include 'vendor/autoload.php';

$requestBody = file_get_contents('php://input'); // Get post body
$input = json_decode($requestBody, true); // convert JSON text into array
$data = $input['data']; // Get the data

// Create new Payment Request from the request
$paymentRequest = \GloBee\PaymentApi\Models\PaymentRequest::fromResponse($data);
```

## Documentation
For more information please view the documentation at: https://globee.com/docs/payment-api/v1

## License

This software is open-sourced software licensed under the [GNU General Public Licence version 3](https://www.gnu.org/licenses/) or later
