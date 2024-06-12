<?php
session_start();
header("Content-Type: application/json");

$response = '{
    "ResultCode": 0, 
    "ResultDesc": "Confirmation Received Successfully"
}';

// Capture the incoming M-PESA response
$mpesaResponse = file_get_contents('php://input');

// Log the response
$logFile = "M_PESAConfirmationResponse.txt";
$log = fopen($logFile, "a");
fwrite($log, $mpesaResponse);
fclose($log);

// Parse the M-PESA response
$mpesaResponseData = json_decode($mpesaResponse, true);

// Check the response status and store the result
if (isset($mpesaResponseData['Body']['stkCallback']['ResultCode'])) {
    $resultCode = $mpesaResponseData['Body']['stkCallback']['ResultCode'];
    $resultDesc = $mpesaResponseData['Body']['stkCallback']['ResultDesc'];

    // Save the result to session
    $_SESSION['mpesa_result'] = [
        'ResultCode' => $resultCode,
        'ResultDesc' => $resultDesc
    ];
}

echo $response;
?>
