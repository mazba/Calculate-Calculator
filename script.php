<?php
$app = require __DIR__.'/vendor/autoload.php';
$file_path = __DIR__.'/'.$argv[1];
try {
    session_start();
    $outputService = new \Mazba\Services\OutputService();
    // Format the csv to array
    $inputs = new \Mazba\Services\InputService($file_path);
    foreach ($inputs->data->rows as $input) {
        // Initiate the calculation
        $calculator = new \Mazba\Calculator\Calculator($input['0'],$input['1'],$input['2'],$input['3'],$input['4'],$input['5']);
        $commission = $calculator->calculate();
        // Print the output
        $outputService->printCommission($commission,$input['5']);
    }
} catch (Exception $e) {
    echo 'ERROR: '.$e->getMessage();
}