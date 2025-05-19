# Commission Fee Calculator

This PHP application calculates commission fees for deposit and withdrawal transactions using real exchange rates and rules based on user type. The supported currencies are **EUR**, **USD**, and **JPY**.

## Features

- Handles two user types: `private` and `business`
- Supports transaction types: `deposit` and `withdraw`
- Currency conversion using [exchangeratesapi.io](https://exchangeratesapi.io/)
- Rules for free withdrawal for private users (up to €1000/week and 3 free withdrawals)
- Different rounding precision for currencies (EUR, USD: 2 decimals, JPY: 0)
- Fully modular and extendable

---

## Requirements

- PHP 8.0 or higher
- Composer

---

## Installation

1. **Clone the repository:**

   ```bash
   git clone https://github.com/davitosadze/commission-fee-calculator.git
   cd commission-fee-calculator
   ```
# commission-fee-calculator
