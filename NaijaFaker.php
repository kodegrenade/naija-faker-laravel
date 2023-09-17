<?php

namespace Kodgrenade\NaijaFaker;

// Define a custom autoloader function
function customAutoloader($className)
{
  $classMap = [
    'Kodegrenade\NaijaFaker\Library\Library' => './src/Library/Library.php',
  ];

  if (isset($classMap[$className])) {
    require $classMap[$className];
  }
}

// Register the custom autoloader
// customAutoloader('Kodegrenade\Naija\Library\Library');
spl_autoload_register(customAutoloader('Kodegrenade\NaijaFaker\Library\Library'));

use Illuminate\Support\Facades\Facade;
use Kodegrenade\NaijaFaker\Library\Library;

class NaijaFaker
{
  private const DEFAULT_LANGUAGES = ['yoruba', 'igbo', 'hausa'];
  private const DEFAULT_GENDER = ['male', 'female'];
  private const DEFAULT_SEPARATORS = [".", ""];
  private const DEFAULT_NETWORKS = ['mtn', 'glo', 'airtel', '9mobile'];

  /**
   * @return string
   */
  protected static function getFacadeAccessor()
  {
    return 'NaijaFaker';
  }

  /**
   * @param {Object} options
   */
  static function config()
  {
    # Load configuration data from config file
  }

  /**
   * Generates Fake Name
   * 
   * @param string language
   * @param string gender
   * 
   * @return string
   */
  static function name(string $language = null, string $gender = null): string
  {
    $fullName = "";

    if (!in_array(strtolower($language), self::DEFAULT_LANGUAGES)) {
      $language = self::DEFAULT_LANGUAGES[array_rand(self::DEFAULT_LANGUAGES)];
    }

    if (!in_array(strtolower($gender), self::DEFAULT_GENDER)) {
      $gender = self::DEFAULT_GENDER[array_rand(self::DEFAULT_GENDER)];
    }

    $loadLibrary = Library::getLibraryData($language);
    $pickFirstName = array_rand($loadLibrary[$gender]);

    if ($gender === "male") {
      $surname = $loadLibrary["male"][array_rand($loadLibrary["male"])];
      $fullName = "{$loadLibrary[$gender][$pickFirstName]} {$surname}";
    } else {
      $surname = $loadLibrary["male"][array_rand($loadLibrary["male"])];
      $fullName = "{$loadLibrary[$gender][$pickFirstName]} {$surname}";
    }

    return $fullName;
  }

  /**
   * Generates Fake Person Data
   * 
   * @param string language
   * @param string gender
   * 
   * @return mixed
   */
  public static function person(string $language = null, string $gender = null): object
  {
    if (!in_array(strtolower($language), self::DEFAULT_LANGUAGES)) {
      $language = self::DEFAULT_LANGUAGES[array_rand(self::DEFAULT_LANGUAGES)];
    }

    if (!in_array(strtolower($gender), self::DEFAULT_GENDER)) {
      $gender = self::DEFAULT_GENDER[array_rand(self::DEFAULT_GENDER)];
    }

    $gender = strtolower($gender);
    $fullName = self::name(strtolower($language), $gender);

    return (object)[
      "title" => self::title(strtolower($gender)),
      "fullname" => $fullName,
      "gender" => ucfirst($gender),
      "email" => self::email($fullName),
      "phone" => self::phoneNumber(),
      "address" => self::address(),
    ];
  }

  /**
   * Generates Fake People Data
   * 
   * @param integer number
   * @param string language
   * @param string gender
   * 
   * @return array
   */
  static function people(int $number = 5, string $language = null, string $gender = null): array
  {
    if ($number && !is_int($number)) {
      throw new \Exception("Value for number must be type of integer.");
    }

    if ($language && !is_string($language)) {
      throw new \Exception("Value for language must be type of string.");
    }

    if ($gender && !is_string($gender)) {
      throw new \Exception("Value for gender must be type of string.");
    }

    $people = [];
    for ($index = 0; $index < $number; $index++) {
      $person = self::person(strtolower($language), strtolower($gender));
      array_push($people, $person);
    }
    return $people;
  }

  /**
   * Generates Fake Title
   * 
   * @param string gender
   * 
   * @return string
   */
  static function title(string $gender = null): string
  {
    if (!in_array(strtolower($gender), self::DEFAULT_GENDER)) {
      $gender = self::DEFAULT_GENDER[array_rand(self::DEFAULT_GENDER)];
    }

    $loadLibrary = Library::getLibraryData("titles");
    $title = $loadLibrary[$gender][array_rand($loadLibrary[$gender])];

    return $title;
  }

  /**
   * Generates Fake Email Address
   * 
   * @param string name
   * @param string extension
   * 
   * @return string
   */
  static function email(string $name, string $extension = null): string
  {
    # do regex for custome extension to either be in the following format @name.com || name.com
    if (!$name) {
      throw new \Exception("Name is not provided.");
    }

    $loadLibrary = Library::getLibraryData("extensions");
    $pickDomain = array_rand($loadLibrary);
    $domain = $loadLibrary[$pickDomain];

    $separator = self::DEFAULT_SEPARATORS[array_rand(self::DEFAULT_SEPARATORS)];
    $name = preg_replace('/[^A-Z0-9]+/i', $separator, strtolower($name));

    return "{$name}{$domain}";
  }

  /**
   * Generates Fake Address
   * 
   * @return string
   */
  static function address(): string
  {
    $loadLibrary = Library::getLibraryData("address");

    $addressTypes = $loadLibrary["types"];
    $addressLocales = $loadLibrary["locale"];
    $pickAddressLocale = array_rand($addressLocales);
    $places = $loadLibrary["places"][$addressLocales[$pickAddressLocale]];
    $states = $loadLibrary["states"][$addressLocales[$pickAddressLocale]];

    $number = rand(1, 200);
    $pickAddressType = array_rand($addressTypes);
    $pickPlace = array_rand($places);
    $pickState = array_rand($states);

    $address = $addressTypes[$pickAddressType];
    $place = $places[$pickPlace];
    $state = $states[$pickState];

    return "{$number} {$address} {$place}, {$state}";
  }

  /**
   * Generates Fake Phone Number
   * 
   * @param string network
   * 
   * @return string
   */
  static function phoneNumber(string $network = null): string
  {
    $network = strtolower($network);
    if (!in_array($network, self::DEFAULT_NETWORKS)) {
      $network = self::DEFAULT_NETWORKS[array_rand(self::DEFAULT_NETWORKS)];
    }

    $loadLibrary = Library::getLibraryData("numbers");
    $networkPrefixs = $loadLibrary["prefix"][$network];

    $pickNetworkPrefix = array_rand($networkPrefixs);
    $selectedPrefix = $networkPrefixs[$pickNetworkPrefix];
    $number = mt_rand(1_000_000, 9_000_000);
    $selectedPrefix = preg_replace('/^0+/', '+234', $selectedPrefix);

    return "{$selectedPrefix}{$number}";
  }

  /**
   * Generates Nigerian States
   * 
   * @return array
   */
  static function states(): array
  {
    $loadLibrary = Library::getLibraryData("states");
    return $loadLibrary;
  }

  /**
   * Generates Nigeria Local Government Areas
   * 
   * @return array
   */
  static function lgas(): array
  {
    $loadLibrary = Library::getLibraryData("lgas");
    return $loadLibrary;
  }
}

$test = NaijaFaker::people(5, "yoruba", "male");
print_r($test);