<?php

namespace App;

class CurrencyConverter
{
    private array $rates = ['EUR' => 1.0]; // Fallback EUR base
    private array $precision = [
        'EUR' => 2,
        'USD' => 2,
        'JPY' => 0,
    ];

    private string $apiUrl = 'https://api.exchangeratesapi.io/latest?access_key=f9ee461fc8cca218c5f9a3c1d78e03ac&symbols=JPY,USD';

    public function __construct()
    {
        $this->loadRatesFromApi();
    }

    private function loadRatesFromApi(): void
    {
        $response = file_get_contents($this->apiUrl);

        if ($response === false) {
            return; 
        }

        $data = json_decode($response, true);

        if (isset($data['rates']) && is_array($data['rates'])) {
            $this->rates = array_merge($this->rates, $data['rates']);
        }
        
    }

    public function toEuro(float $amount, string $currency): float
    {
        if (!isset($this->rates[$currency]) || $this->rates[$currency] == 0) {
            throw new \InvalidArgumentException("Invalid currency or rate for: $currency");
        }

        return $amount / $this->rates[$currency];
    }

    public function fromEuro(float $amount, string $currency): float
    {
        if (!isset($this->rates[$currency])) {
            throw new \InvalidArgumentException("Invalid currency or rate for: $currency");
        }

        return $amount * $this->rates[$currency];
    }

    public function getCurrencyRate(string $currency): float
    {
        return $this->rates[$currency] ?? 1.0;
    }

    public function getCurrencyPrecision(string $currency): int
    {
        return $this->precision[$currency] ?? 2;
    }
}
