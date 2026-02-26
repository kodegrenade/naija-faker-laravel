# Laravel NaijaFaker

## Introduction 👋

A comprehensive package that generates fake, culturally authentic Nigerian data — names, addresses, phone numbers, bank accounts, universities, companies, and much more. Supports three major ethnic groups: **Yoruba**, **Igbo**, and **Hausa**.

> NB: Nigeria as a country has so many languages. `Yoruba`, `Igbo` and `Hausa` are the only languages supported at the moment.

## Installation

Install via composer

```bash
composer require kodegrenade/naija-faker-laravel
```

Add service provider to the `config/app.php` file

```php
<?php
  /*
  |--------------------------------------------------------------------------
  | Autoloaded Service Providers
  |--------------------------------------------------------------------------
  */

  'providers' => [
    ...
    Kodegrenade\NaijaFaker\NaijaFakerServiceProvider::class,
  ];
...
```

Add alias to the `config/app.php` file

```php
  'aliases' => [
    ...
    'NaijaFaker' => Kodegrenade\NaijaFaker\NaijaFaker::class,
  ];
...
```

## Usage 🧨

> **NOTE**
> All methods return associative arrays or strings.

---

### Seeded / Deterministic Mode

Set a seed for reproducible output (useful for testing):

```php
<?php

NaijaFaker::seed(12345);
$name1 = NaijaFaker::name(); // always the same

NaijaFaker::seed(null); // reset to random mode
```

### Configuration

Set default language, gender, or network:

```php
<?php

NaijaFaker::config([
  'language' => 'yoruba',
  'gender' => 'male',
  'network' => 'mtn',
]);
```

---

### Person

Generate fake person data.

```php
<?php

NaijaFaker::person(string $language, string $gender);
```

#### Sample
```php
$person = NaijaFaker::person('yoruba', 'male');
```

```php
[
  'title' => 'Engr.',
  'firstName' => 'Akintunde',
  'lastName' => 'Owoyele',
  'fullName' => 'Akintunde Owoyele',
  'email' => 'akintunde.owoyele@gmail.com',
  'phone' => '+2349093636382',
  'address' => 'Plot 63, Ebubedike Uzoma Avenue, Awka',
]
```

### People

Generate multiple fake people.

```php
<?php

NaijaFaker::people(int $count = 10, string $language, string $gender);
```

### Name

Generate a fake name.

```php
<?php

$name = NaijaFaker::name('hausa', 'male');
// => "Ahmed Maikudi"
```

### Title

Generate a fake Nigerian title.

```php
<?php

$title = NaijaFaker::title('male');
// => "Chief"
```

### Email Address

Generate a fake email address.

```php
<?php

$email = NaijaFaker::email('Temitope Ayotunde');
// => "temitope.ayotunde@gmail.com"

$email = NaijaFaker::email('Temitope Ayotunde', 'workplace.com');
// => "temitopeayotunde@workplace.com"
```

### Address

Generate a fake Nigerian address.

```php
<?php

$address = NaijaFaker::address();
// => "Plot 45, Oluwaseun Adedayo Street, Ibadan"
```

### Phone Number

Generate a fake Nigerian phone number.

```php
<?php

NaijaFaker::phoneNumber(string $network);
```

> **NOTE**: Supported networks are `mtn`, `glo`, `9mobile` & `airtel`

```php
$phone = NaijaFaker::phoneNumber('mtn');
// => "+2347037653761"
```

### States & LGAs

```php
<?php

$states = NaijaFaker::states(); // all 37 Nigerian states
$lgas = NaijaFaker::lgas();     // all 774 LGAs
```

### Identity & Financial

#### BVN (Bank Verification Number)
```php
$bvn = NaijaFaker::bvn();
// => "22345678901"
```

#### NIN (National Identification Number)
```php
$nin = NaijaFaker::nin();
// => "12345678901"
```

#### Bank Account
```php
$account = NaijaFaker::bankAccount();
// => ['bankName' => 'Access Bank', 'bankCode' => '044', 'accountNumber' => '1234567890']

$account = NaijaFaker::bankAccount('Zenith Bank');
// => ['bankName' => 'Zenith Bank', 'bankCode' => '057', 'accountNumber' => '0987654321']
```

---

### Geographic Consistency

Generate persons where **name ethnicity, state, and LGA all match geographically**.

#### Consistent Person
```php
$person = NaijaFaker::consistentPerson('yoruba', 'male');
```

```php
[
  'title' => 'Chief',
  'firstName' => 'Adebayo',
  'lastName' => 'Ogunlesi',
  'fullName' => 'Adebayo Ogunlesi',
  'email' => 'adebayo.ogunlesi@gmail.com',
  'phone' => '+2348031234567',
  'address' => 'Plot 45, Oluwaseun Adedayo Street, Ibadan',
  'state' => 'Oyo',        // matches Yoruba region
  'lga' => 'Ibadan North',  // belongs to Oyo state
]
```

#### Consistent People
```php
$people = NaijaFaker::consistentPeople(5, 'igbo', 'female');
```

---

### Records

#### License Plate
```php
$plate = NaijaFaker::licensePlate();         // => "KAN-234XY"
$plate = NaijaFaker::licensePlate('Lagos');   // => "LAG-567AB"
```

#### Company
```php
$company = NaijaFaker::company();
// => ['name' => 'Pan-African Technologies Ltd', 'rcNumber' => 'RC-456789', 'industry' => 'Technology']
```

#### University
```php
$uni = NaijaFaker::university();
// => ['name' => 'University of Lagos', 'abbreviation' => 'UNILAG', 'state' => 'Lagos', 'type' => 'federal']
```

#### Education Record
```php
$edu = NaijaFaker::educationRecord('yoruba');
// => ['university' => 'University of Ibadan', 'abbreviation' => 'UI', 'degree' => 'B.Sc', 'course' => 'Computer Science', 'graduationYear' => 2019]
```

#### Work Record
```php
$work = NaijaFaker::workRecord();
// => ['company' => 'Global Finance Holdings', 'position' => 'Software Engineer', 'industry' => 'Technology', 'startYear' => 2020]
```

#### Vehicle Record
```php
$vehicle = NaijaFaker::vehicleRecord('Lagos');
// => ['licensePlate' => 'LAG-234XY', 'make' => 'Toyota', 'model' => 'Corolla', 'year' => 2021, 'color' => 'Silver']
```

---

### Personal Data

#### Date of Birth
```php
$dob = NaijaFaker::dateOfBirth();
// => ['date' => '1990-03-15', 'age' => 35]

$dob = NaijaFaker::dateOfBirth(['minAge' => 25, 'maxAge' => 40]);
```

#### Marital Status
```php
$status = NaijaFaker::maritalStatus();
// => "Married"
```

#### Blood Group & Genotype
```php
$bg = NaijaFaker::bloodGroup();  // => "O+"
$gt = NaijaFaker::genotype();    // => "AA"
```

#### Salary
```php
$salary = NaijaFaker::salary();
// => ['amount' => 450000, 'currency' => 'NGN', 'level' => 'mid', 'frequency' => 'monthly']

$salary = NaijaFaker::salary(['level' => 'senior']);
```

#### Next of Kin
```php
$kin = NaijaFaker::nextOfKin('yoruba', 'female');
// => ['fullName' => 'Folake Adeyemi', 'relationship' => 'Sister', 'phone' => '+234...', 'address' => '...']
```

---

### Detailed Person

Generate a **complete identity** with person, personal data, education, work, and vehicle all in one call:

```php
$person = NaijaFaker::detailedPerson('yoruba', 'male');
```

```php
[
  // Person
  'title' => 'Chief',
  'firstName' => 'Adebayo',
  'lastName' => 'Ogunlesi',
  'fullName' => 'Adebayo Ogunlesi',
  'email' => 'adebayo.ogunlesi@gmail.com',
  'phone' => '+2348031234567',
  'address' => 'Plot 45, Oluwaseun Street, Ibadan',
  'state' => 'Oyo',
  'lga' => 'Ibadan North',
  // Personal
  'dateOfBirth' => ['date' => '1990-03-15', 'age' => 35],
  'maritalStatus' => 'Married',
  'bloodGroup' => 'O+',
  'genotype' => 'AA',
  'salary' => ['amount' => 450000, 'currency' => 'NGN', 'level' => 'mid', 'frequency' => 'monthly'],
  'nextOfKin' => ['fullName' => 'Folake Adeyemi', 'relationship' => 'Spouse', 'phone' => '...', 'address' => '...'],
  // Records
  'education' => ['university' => 'University of Ibadan', 'abbreviation' => 'UI', 'degree' => 'B.Sc', 'course' => 'Computer Science', 'graduationYear' => 2019],
  'work' => ['company' => 'Pan-African Solutions Ltd', 'position' => 'Software Engineer', 'industry' => 'Technology', 'startYear' => 2020],
  'vehicle' => ['licensePlate' => 'OYO-234XY', 'make' => 'Toyota', 'model' => 'Corolla', 'year' => 2021, 'color' => 'Silver'],
]
```

```php
$people = NaijaFaker::detailedPeople(5, 'igbo');
```

---

### Export

Export generated data as **JSON** or **CSV**:

```php
$json = NaijaFaker::export('person', 10, 'json');
$csv = NaijaFaker::export('detailedPerson', 5, 'csv');
$csv = NaijaFaker::export('consistentPerson', 10, 'csv');
```

CSV output uses **dot notation** for nested fields (e.g., `education.university`, `salary.amount`).

---

### Custom Providers

Register your own data generators:

```php
// Register
NaijaFaker::registerProvider('religion', function ($faker) {
  $religions = ['Christianity', 'Islam', 'Traditional'];
  return $religions[array_rand($religions)];
});

// Use
NaijaFaker::generate('religion'); // => "Islam"

// List all registered
NaijaFaker::listProviders(); // => ['religion']
```

> **NOTE**: Custom providers cannot override built-in methods.

---

### Quick Usage

You can give the package a quick spin by running the following artisan command:

```bash
php artisan faker:generator
```

### Tests

```bash
composer test tests
```

## Disclaimer :warning:
See [`DISCLAIMER`](DISCLAIMER.md) for details.

## Contribution

Please feel free to fork the package and contribute by submitting a pull request to enhance the functionalities.

## License
Naija Faker is release under the MIT License. See [`LICENSE`](LICENSE) for details.

## Feedback
If you have any feedback, please reach out to me at brhamix@gmail.com

## Author
- [@kodegrenade](https://www.github.com/kodegrenade)
