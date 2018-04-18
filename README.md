# GloBee Payment API
This is a library to integrate your system with the GloBee Payment API
to manage and accept crypto payments on through our system.

## Installation with Composer
Run the following command in your project to add this package:
```bash
composer require globee/payment-api
```

## Authenticate with GloBee
To receive a valid X-AUTH-ID, complete the following steps:
1) Sign into GloBee, and navigate to the Payment API section on the backend panel.
2) Click on the "Add New Key" button, complete the "Label" field and click "Create Key".
3) Copy the key created and store it somewhere in your code.

## Usage Example
To create an invoice on GloBee and receive a redirect to a payment interstitial, you can copy and modify the below code.
### Create new Payment Request
```php
<?php

include 'vendor/autoload.php';

$connector = new \GloBee\PaymentApi\Connectors\GloBeeCurlConnector(
    'YOUR_UNIQUE_API_KEY'
);
$paymentApi = new \GloBee\PaymentApi\PaymentApi($connector);

$paymentRequest = new \GloBee\PaymentApi\Models\PaymentRequest();

$paymentRequest->setTotal(123.45);
$paymentRequest->setCustomerEmail('example@email.com');

$response = $paymentApi->createPaymentRequest($paymentRequest);

$paymentRequestId = $response->getId(); // Save this ID to know when payment has been made
$redirectUrl = $response->getRedirectUrl(); // Redirect your client to this URL to make payment
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