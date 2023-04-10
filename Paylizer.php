<?php

// Define the namespace and class for the Paylizer module
namespace PaylizerModule;

class Paylizer
{
    // Define private properties for the module
    private $apiKey;
    private $apiSecret;
    private $apiUrl;
    
    // Define a constructor function that sets the private properties
    public function __construct($apiKey, $apiSecret, $apiUrl)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->apiUrl = $apiUrl;
    }
    
    // Define a public function for processing payments
    public function processPayment($amount, $currency, $cardNumber, $cardExpiryMonth, $cardExpiryYear, $cardCvv)
    {
        // Validate the input parameters
        if (!$this->validateAmount($amount)) {
            throw new Exception("Invalid amount");
        }
        if (!$this->validateCurrency($currency)) {
            throw new Exception("Invalid currency");
        }
        if (!$this->validateCardNumber($cardNumber)) {
            throw new Exception("Invalid card number");
        }
        if (!$this->validateCardExpiryMonth($cardExpiryMonth)) {
            throw new Exception("Invalid card expiry month");
        }
        if (!$this->validateCardExpiryYear($cardExpiryYear)) {
            throw new Exception("Invalid card expiry year");
        }
        if (!$this->validateCardCvv($cardCvv)) {
            throw new Exception("Invalid card CVV");
        }
        
        // Call the payment gateway API with the input parameters
        $response = $this->callApi('process_payment', array(
            'amount' => $amount,
            'currency' => $currency,
            'card_number' => $cardNumber,
            'card_expiry_month' => $cardExpiryMonth,
            'card_expiry_year' => $cardExpiryYear,
            'card_cvv' => $cardCvv,
        ));
        
        // Return the response from the API
        return $response;
    }
    
    // Define private functions for validating input parameters
    private function validateAmount($amount)
    {
        return is_numeric($amount) && $amount > 0;
    }
    private function validateCurrency($currency)
    {
        $validCurrencies = array('USD', 'EUR', 'GBP');
        return in_array($currency, $validCurrencies);
    }
    private function validateCardNumber($cardNumber)
    {
        return preg_match('/^\d{16}$/', $cardNumber);
    }
    private function validateCardExpiryMonth($cardExpiryMonth)
    {
        return preg_match('/^0[1-9]|1[0-2]$/', $cardExpiryMonth);
    }
    private function validateCardExpiryYear($cardExpiryYear)
    {
        return preg_match('/^\d{4}$/', $cardExpiryYear);
    }
    private function validateCardCvv($cardCvv)
    {
        return preg_match('/^\d{3}$/', $cardCvv);
    }
    
    // Define a private function for calling the payment gateway API
    private function callApi($method, $params)
    {
        // Construct the request URL
        $requestUrl = $this->apiUrl . '/' . $method;
        
        // Add the API key and secret to the request parameters
        $params['api_key'] = $this->apiKey;
        $params['api_secret'] = $this->apiSecret;
        
        // Initialize a cURL session for making the API call
        $ch = curl_init();
        
        // Set the cURL options
        curl_setopt($ch, CURLOPT_URL, $requestUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Execute the cURL session and capture the response
        $response = curl_exec($ch);
        
        // Check for cURL errors and throw an exception if necessary
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }
        
        // Close the cURL session
        curl_close($ch);
        
        // Decode the JSON response from the API
        $decodedResponse = json_decode($response, true);
        
        // Check for errors in the API response and throw an exception if necessary
        if ($decodedResponse['status'] != 'success') {
            throw new Exception($decodedResponse['message']);
        }
        
        // Return the response data from the API
        return $decodedResponse['data'];
    }
}
