# Payment Gateway Module
This PHP module provides a simple way to process payments through a payment gateway API. It can be used to validate input parameters and call the API with the necessary parameters.

## Installation
To use this module, you should include the Paylizer.php file in your PHP project:

`require_once('Paylizer.php');`

## Usage
To use this module, you should create a new instance of the Paylizer class with your API key, secret, and API URL:

`$gateway = new PaylizerModule\Paylizer('YOUR_API_KEY', 'YOUR_API_SECRET', 'https://api.paymentgateway.com');`

You can then use the processPayment method to process a payment:

`$response = $gateway->processPayment($amount, $currency, $cardNumber, $cardExpiryMonth, $cardExpiryYear, $cardCvv);`

This method will validate the input parameters and call the payment gateway API with the necessary parameters. It will then return the response from the API.

## Input Parameters
The processPayment method accepts the following input parameters:

- **$amount** - the amount of the payment

- **$currency** - the currency of the payment (e.g. USD, EUR, GBP)

- **$cardNumber** - the card number of the payment

- **$cardExpiryMonth** - the expiry month of the card (formatted as 'MM')

- **$cardExpiryYear** - the expiry year of the card (formatted as 'YYYY')

- **$cardCvv** - the CVV of the card

## Validation
The input parameters are validated before calling the payment gateway API. If any of the input parameters are invalid, an exception will be thrown.

## Security
The API key and secret are kept private and are not exposed in the code. The payment gateway API is called using HTTPS to ensure that the data is encrypted in transit.
