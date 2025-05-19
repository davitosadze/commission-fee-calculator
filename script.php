<?php

require 'bootstrap.php';

use App\Calculator;

function getCommission(
    Calculator $calculator,
    int $userId,
    string $userType,
    string $transactionType,
    string $date,
    float $amount,
    string $currency
): float {
    return match ($transactionType) {
        'withdraw' => match ($userType) {
            'private' => $calculator->calculatePrivateWithdraw($userId, $date, $amount, $currency),
            'business' => $calculator->calculateBusinessWithdraw($amount, $currency),
            default => throw new InvalidArgumentException("Unknown user type: $userType"),
        },
        'deposit' => $calculator->calculateDeposit($amount, $currency),
        default => throw new InvalidArgumentException("Unknown transaction type: $transactionType"),
    };
}

$filename = $argv[1] ?? null;
if (!$filename || !file_exists($filename)) {
    exit("CSV file not found.\n");
}

$calculator = new Calculator();

if (($handle = fopen($filename, 'r')) === false) {
    exit("Failed to open CSV file.\n");
}

while (($data = fgetcsv($handle, 0, ",", '"', "\\")) !== false) {
    try {
        [$date, $userId, $userType, $transactionType, $amount, $currency] = $data;
        $userId = (int)$userId;
        $amount = (float)$amount;

        $commission = getCommission($calculator, $userId, $userType, $transactionType, $date, $amount, $currency);
        $precision = $calculator->currencyConverter->getCurrencyPrecision($currency);

        echo formatAmount($commission, $precision) . "\n";
    } catch (InvalidArgumentException $e) {
        echo $e->getMessage() . "\n";
    }
}

function formatAmount(float $amount, int $precision): string {
    return number_format($amount, $precision, '.', '');
}

fclose($handle);
