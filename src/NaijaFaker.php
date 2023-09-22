<?php

/*
 * This file is part of the Laravel NaijaFaker package.
 *
 * (c) Temitope Ayotunde <brhamix@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace Kodegrenade\NaijaFaker;

use Illuminate\Support\Facades\Facade;
use Kodegrenade\NaijaFaker\Library\Library;

class NaijaFaker extends Facade
{
  private const DEFAULT_LANGUAGES = ['yoruba', 'igbo', 'hausa'];
  private const DEFAULT_GENDER = ['male', 'female'];
  private const DEFAULT_SEPARATORS = [".", ""];
  private const DEFAULT_NETWORKS = ['mtn', 'glo', 'airtel', '9mobile'];

  /**
   * @return string
   */
  protected static function getFacadeAccessor(): string
  {
    return 'NaijaFaker';
  }

  /**
   * Generates Fake Name
   * 
   * @param string $language
   * @param string $gender
   * 
   * @return string
   */
  public static function name(string $language = null, string $gender = null): string
  {
    $fullName = "";

    $language = is_string($language) ? strtolower($language) : '';
    if (!in_array(strtolower($language), self::DEFAULT_LANGUAGES)) {
      $language = self::DEFAULT_LANGUAGES[array_rand(self::DEFAULT_LANGUAGES)];
    }

    $gender = is_string($gender) ? strtolower($gender) : '';
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
   * @param string $language
   * @param string $gender
   * 
   * @return mixed
   */
  public static function person(string $language = null, string $gender = null): object
  {
    $language = is_string($language) ? strtolower($language) : '';
    if (!in_array($language, self::DEFAULT_LANGUAGES)) {
      $language = self::DEFAULT_LANGUAGES[array_rand(self::DEFAULT_LANGUAGES)];
    }

    $gender = is_string($gender) ? strtolower($gender) : '';
    if (!in_array($gender, self::DEFAULT_GENDER)) {
      $gender = self::DEFAULT_GENDER[array_rand(self::DEFAULT_GENDER)];
    }

    $fullName = self::name($language, $gender);

    return (object)[
      "title" => self::title($gender),
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
   * @param int $number
   * @param string $language
   * @param string $gender
   * 
   * @return array
   */
  public static function people(int $number = 5, string $language = null, string $gender = null): array
  {
    $language = is_string($language) ? strtolower($language) : '';
    $gender = is_string($gender) ? strtolower($gender) : '';

    $people = [];
    for ($index = 0; $index < $number; $index++) {
      $person = self::person($language, $gender);
      array_push($people, $person);
    }
    return $people;
  }

  /**
   * Generates Fake Title
   * 
   * @param string $gender
   * 
   * @return string
   */
  public static function title(string $gender = null): string
  {
    $gender = is_string($gender) ? strtolower($gender) : '';
    if (!in_array($gender, self::DEFAULT_GENDER)) {
      $gender = self::DEFAULT_GENDER[array_rand(self::DEFAULT_GENDER)];
    }

    $loadLibrary = Library::getLibraryData("titles");
    $title = $loadLibrary[$gender][array_rand($loadLibrary[$gender])];

    return $title;
  }

  /**
   * Generates Fake Email Address
   * 
   * @param string $name
   * @param string $extension
   * 
   * @return string
   */
  public static function email(string $name, string $extension = null): string
  {
    if (!$name) {
      throw new \Exception("Name is not provided.");
    }

    $extension = is_string($extension) ? strtolower($extension) : '';
    if ($extension) $extension = preg_replace('/[^.a-zA-Z0-9]/', '', $extension);

    $loadLibrary = Library::getLibraryData("extensions");
    $pickDomain = array_rand($loadLibrary);
    $domain = ($extension) ? '@' . $extension : $extension ?? $loadLibrary[$pickDomain];

    $separator = self::DEFAULT_SEPARATORS[array_rand(self::DEFAULT_SEPARATORS)];
    $name = preg_replace('/[^a-zA-Z0-9]+/i', $separator, strtolower($name));

    return "{$name}{$domain}";
  }

  /**
   * Generates Fake Address
   * 
   * @return string
   */
  public static function address(): string
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
   * @param string $network
   * 
   * @return string
   */
  public static function phoneNumber(string $network = null): string
  {
    $network = is_string($network) ? strtolower($network) : '';
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
  public static function states(): array
  {
    $loadLibrary = Library::getLibraryData("states");
    return $loadLibrary;
  }

  /**
   * Generates Nigeria Local Government Areas
   * 
   * @return array
   */
  public static function lgas(): array
  {
    $loadLibrary = Library::getLibraryData("lgas");
    return $loadLibrary;
  }
}
