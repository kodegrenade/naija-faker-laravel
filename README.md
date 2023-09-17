# Laravel NaijaFaker ▲

## Introduction 🖖

This is a simple package that generates fake typical Nigerian data ranging from `name`, `address`, `phone number`, `lgas` and `states`. This can be used mostly for generating fake data for your application.

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
  |
  | The service providers listed here will be automatically loaded on the
  | request to your application. Feel free to add your own services to
  | this array to grant expanded functionality to your applications.
  |
  */

  'providers' => [
    ...
    Kodegrenade\NaijaFaker\OtpServiceProvider::class,
  ];
...
```

Add alias to the `config/app.php` file

```php
  /*
  |--------------------------------------------------------------------------
  | Class Aliases
  |--------------------------------------------------------------------------
  |
  | This array of class aliases will be registered when this application
  | is started. However, feel free to register as many as you wish as
  | the aliases are "lazy" loaded so they don't hinder performance.
  |
  */

  'aliases' => [
    ...
    'NaijaFaker' => Kodegrenade\NaijaFaker\NaijaFaker::class,
  ];
...
```

## Usage 🧨

>**NOTE**<br>
> Response are returned as objects or array depending on the method used.

> NB: Nigeria as a country has some many languages. `Yoruba`, `Igbo` and `Hausa` are the only languages supported at the moment.

### Generate Fake Data

Generate fake data by calling the faker methods.

```php
<?php

NaijaFaker::person(string $language, string $sex);
```
* `$language`: The language of the fake person's data to be generated.
* `$sex`: The gender of the person's data to be generate.

## Sample
```php
<?php

$faker = NaijaFaker::person('yoruba', 'male');
```

Output

```object
{
  title: 'Engr.',
  firstName: 'Akintunde',
  lastName: 'Owoyele',
  fullName: 'Akintunde Owoyele',
  email: 'akintunde.owoyele@gmail.com',
  phone: '+2349093636382',
  address: '63, Ebubedike Uzoma Avenue, Awka'
}
```

### Quick Usage

You can give the package a quick spin by running the following artisan command:

```bash
php artisan faker:generator
```

## Contribution

Please feel free to fork the package and contribute by submitting a pull request to enhance the functionalities.

## License
Naija Faker is release under the MIT License. See [`LICENSE`](LICENSE) for details.

## Feedback
If you have any feedback, please reach out to me at brhamix@gmail.com

## Author
- [@kodegrenade](https://www.github.com/kodegrenade)