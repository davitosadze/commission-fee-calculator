# 💰 Commission Fee Calculator

This PHP command-line application calculates commission fees for deposit and withdrawal transactions using real-time exchange rates and rules based on user and transaction types.

## 🌍 Supported Currencies

- EUR
- USD
- JPY

## 👤 Supported User Types

- `private`
- `business`

## 💳 Supported Transaction Types

- `deposit`
- `withdraw`

---

## 🧠 Features

- Free withdrawals up to €1000/week and 3 transactions for private users.
- Fixed percentage fees for business users and deposits.
- Real-time exchange rates via [exchangeratesapi.io](https://exchangeratesapi.io/).
- Currency-specific rounding:
  - EUR & USD: 2 decimal places
  - JPY: No decimals (rounded to integers)
- Clean, testable, object-oriented code.

---

## 📦 Requirements

- PHP 8.0 or higher
- Composer

---

## 🛠️ Installation

1. **Clone the repository:**

   ```bash
   git clone https://github.com/davitosadze/commission-fee-calculator.git
   cd commission-fee-calculator

   ```

2. **Install dependencies:**
   ```bash
   composer install

   ```

---

## Usage

1. **Run Code:**

   ```bash
   php script.php input.csv
   ```
