<?php

namespace App;

use App\CurrencyConverter;

class Calculator
{
    protected array $userWithdrawals = [];
    public CurrencyConverter $currencyConverter;

    private float $deposit_fee = 0.03;
    private float $private_withdrawal_fee = 0.3;
    private float $business_withdrawal_fee = 0.5;

    public function __construct()
    {
        $this->currencyConverter = new CurrencyConverter();
    }

    public function calculateDeposit(float $amount, string $currency): float
    {
        $commission = ($amount * $this->deposit_fee) / 100;
        $precision = $this->currencyConverter->getCurrencyPrecision($currency);
        return $this->roundUp($commission, $precision);
    }

    public function calculatePrivateWithdraw(int $userId, string $date, float $amount, string $currency): float
    {
        $weekStart = date('o-W', strtotime($date));
        $eurAmount = $this->currencyConverter->toEuro($amount, $currency);

        if (!isset($this->userWithdrawals[$userId][$weekStart])) {
            $this->userWithdrawals[$userId][$weekStart] = ['count' => 0, 'total' => 0.0];
        }

        $weekData = &$this->userWithdrawals[$userId][$weekStart];
        $commission = 0.0;

        if ($weekData['count'] < 3) {
            $freeLeft = max(0, 1000.0 - $weekData['total']);
            if ($eurAmount > $freeLeft) {
                $taxableAmountEUR = $eurAmount - $freeLeft;
                $commission = $taxableAmountEUR * ($this->private_withdrawal_fee / 100);
            }
        } else {
            $commission = $eurAmount * ($this->private_withdrawal_fee / 100);
        }

        $weekData['count']++;
        $weekData['total'] += $eurAmount;

        $commissionInOriginal = $this->currencyConverter->fromEuro($commission, $currency);
        $precision = $this->currencyConverter->getCurrencyPrecision($currency);
        return $this->roundUp($commissionInOriginal, $precision);
    }

    public function calculateBusinessWithdraw(float $amount, string $currency): float
    {
        $commission = ($amount * $this->business_withdrawal_fee) / 100;
        $precision = $this->currencyConverter->getCurrencyPrecision($currency);
        return $this->roundUp($commission, $precision);
    }

    private function roundUp(float $amount, int $precision): float
    {
        $multiplier = pow(10, $precision);
        $scaled = round($amount * $multiplier, 8);

        if (floor($scaled) == $scaled) {
            return (float)$amount;
        }

        return ceil($scaled) / $multiplier;
    }
}