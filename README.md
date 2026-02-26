# Laravel NaijaFaker

## Introduction 👋

This is a simple package that generates fake typical Nigerian data ranging from `name`, `address`, `phone number`, `lgas` and `states`. This can be used mostly for generating fake data for your application.

> NB: Nigeria as a country has some many languages. `Yoruba`, `Igbo` and `Hausa` are the only languages supported at the moment.

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

### Person

Generate fake person data by calling the `person` faker method.

```php
<?php

NaijaFaker::person(string $language, string $gender);
```
* `$language (optional | default = random value)`: The language of the fake person's data to be generated.
* `$gender (optional | default = random value)`: The gender of the person's data to be generate.

#### Sample
```php
<?php

$person = NaijaFaker::person('yoruba', 'male');
```

This will generate a fake person data with yoruba names.

```object
{
  "title": 'Engr.',
  "firstName": 'Akintunde',
  "lastName": 'Owoyele',
  "fullName": 'Akintunde Owoyele',
  "email": 'akintunde.owoyele@gmail.com',
  "phone": '+2349093636382',
  "address": '63, Ebubedike Uzoma Avenue, Awka'
}
```

### People

Generate fake people data by calling the `people` faker method.

```php
<?php

NaijaFaker::people(int $number = 5, string $language, string $gender);
```

* `$number (optional | default = 5)`: The number of person's to be added to the people list. 
* `$language (optional | default = random value)`: The language of the fake persons data to be generated in the people list.
* `$gender (optional | default = random value)`: The gender of the people to be generate.

#### Sample
```php
<?php

$people = NaijaFaker::people(3, 'igbo', 'female');
```

This will generate three (3) female fake persons with igbo names.
```bash
[
  {
    "title" => "Dr."
    "fullname" => "Chinweuba Enyinnaya"
    "gender" => "Female"
    "email" => "chinweuba.enyinnaya@protonmail.com"
    "phone" => "+2348088176205"       
    "address" => "86 Crescent Itu, Imo"
  },
  {
    "title" => "Prof."
    "fullname" => "Akachukwu Ndubisi"
    "gender" => "Female"
    "email" => "akachukwundubisi@yahoo.com"
    "phone" => "+2349025762928"
    "address" => "199 Avenue Ilobu, Oyo"
  },
  {
    "title" => "Mrs."
    "fullname" => "Ngozi Zeribe"
    "gender" => "Female"
    "email" => "ngozi.zeribe@gmail.com"
    "phone" => "+2347034743668"
    "address" => "156 Crescent Bonny, Bayelsa"
  }
]
```

### Name

Generate fake name.

```php
<?php

NaijaFaker::name(string $language, string $gender);
```
* `$language (optional | default = random value)`: The language of the fake name to be generated.
* `$gender (optional | default = random value)`: The gender of the name to be generated.

#### Sample

```php
<?php

$name = NaijaFaker::name('hausa', 'male');
```

This will generate fake male Hausa name.
```bash
Ahmed Maikudi
```

### Title

Generate fake title.

```php
<?php

NaijaFaker::title(string $gender);
```

* `$gender (optional | default = random value)`: The gender of the title to be generated.

#### Sample

```php
<?php

$name = NaijaFaker::title('male');
```

This will generate fake male title.
```bash
Mr.
```

### Email Address

Generate fake email address.

```php
<?php

NaijaFaker::email(string $name, string $extension);
```

* `$name`: The name of the email address to be generated.
* `$extension (optional | default = random value)`: The domain extension of the email address.

#### Sample

```php
<?php

# without domain extension
$email = NaijaFaker::email('Temitope Ayotunde');

# with domain extension
$email = NaijaFaker::email('Temitope Ayotunde', 'workplace.com');
```

This will generate fake email address.
```bash
# without extenstion
temitopeayotunde@hotmail.com

# with extension
temitopeayotunde@workplace.com
```

### Address

Generate fake house address.

```php
<?php

NaijaFaker::address();
```

#### Sample

```php
<?php

$address = NaijaFaker::address();
```

This will generate fake email address.
```bash
188 Crescent Bori City, Enugu
```

### Phone Number

Generate phone number.

```php
<?php

NaijaFaker::phoneNumber(string $network);
```

* `$network (optional | default = random value)`: The network of the phone number to be generated.

>**NOTE**<br>
> Supported networks are `Mtn`, `Glo`, `9mobile` & `Airtel`

#### Sample

```php
<?php

$email = NaijaFaker::phoneNumber('mtn');
```

This will generate phone number.

```bash
+2347037653761
```

### States

Generate nigerian states.

```php
<?php

NaijaFaker::states();
```

#### Sample

```php
<?php

$states = NaijaFaker::states();
```

This will return all the Nigerian states.

```bash
[
  "Abia",
  "Adamawa",
  "Akwa Ibom",
  "Anambra",
  "Bauchi",
  "Bayelsa",
  "Benue",
  "Borno",
  "Cross River",
  "Delta",
  "Ebonyi",
  ...
]
```

### LGAs

Generate Nigerian Local Government Areas.

```php
<?php

NaijaFaker::lgas();
```

#### Sample

```php
<?php

$states = NaijaFaker::lgas();
```

This will return all the Nigerian Local Government Areas.

```bash
[
  "Aba North",
  "Aba South",
  "Arochukwu",
  "Bende",
  "Ikwuano",
  "Isiala Ngwa North",
  "Isiala Ngwa South",
  "Isuikwuato",
  "Obi Ngwa",
  "Ohafia",
  "Osisioma",
  "Ugwunagbo",
  "Ukwa East",
  "Ukwa West",
  "Umuahia North",
  "Umuahia South",
  "Umu Nneochi",
  "Demsa",
  "Fufure",
  "Ganye",
  "Gayuk",
  "Gombi",
  "Grie",
  "Hong",
  ...
]
```

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
