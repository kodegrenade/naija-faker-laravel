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
use Kodegrenade\NaijaFaker\Exceptions\NaijaFakerException;

class NaijaFaker extends Facade
{
  private const VALID_LANGUAGES = ['yoruba', 'igbo', 'hausa'];
  private const VALID_GENDERS = ['male', 'female'];
  private const VALID_SEPARATORS = [".", ""];
  private const VALID_NETWORKS = ['mtn', 'glo', 'airtel', '9mobile'];

  /**
   * Internal PRNG state
   * @var callable|null
   */
  private static $prng = null;

  /**
   * Custom provider registry
   * @var array<string, callable>
   */
  private static array $customProviders = [];

  /**
   * Stored config defaults
   */
  private static ?string $language = null;
  private static ?string $gender = null;
  private static ?string $network = null;

  /**
   * @return string
   */
  protected static function getFacadeAccessor(): string
  {
    return 'NaijaFaker';
  }

  /**
   * Set a seed for deterministic output.
   * Call without arguments or with null to reset to non-deterministic mode.
   *
   * @param int|null $value
   * @throws NaijaFakerException
   */
  public static function seed(?int $value = null): void
  {
    if ($value === null) {
      self::$prng = null;
      return;
    }

    $s = $value & 0xFFFFFFFF;
    self::$prng = function () use (&$s) {
      $s = self::mask32($s + 0x6D2B79F5);
      $t = self::mul32($s ^ self::rshift32($s, 15), 1 | $s);
      $t = self::mask32($t + self::mul32($t ^ self::rshift32($t, 7), 61 | $t)) ^ $t;
      return self::mask32($t ^ self::rshift32($t, 14)) / 4294967296;
    };
  }

  /**
   * Internal random number generator.
   * Uses seeded PRNG if set, otherwise mt_rand.
   *
   * @return float random float between 0 (inclusive) and 1 (exclusive)
   */
  private static function _random(): float
  {
    if (self::$prng) {
      return (self::$prng)();
    }
    return mt_rand() / mt_getrandmax();
  }

  /**
   * Internal helper: random integer in range [min, max].
   */
  private static function _randomInt(int $min, int $max): int
  {
    return $min + (int) floor(self::_random() * ($max - $min + 1));
  }

  /**
   * Internal helper: pick a random element from an array.
   *
   * @param array $arr
   * @return mixed
   */
  private static function _randomElement(array $arr)
  {
    return $arr[(int) floor(self::_random() * count($arr))];
  }

  /**
   * Mask value to 32-bit unsigned integer.
   */
  private static function mask32(int $n): int
  {
    return $n & 0xFFFFFFFF;
  }

  /**
   * Unsigned right shift for 32-bit values (equivalent to >>> in JS).
   */
  private static function rshift32(int $n, int $bits): int
  {
    return ($n & 0xFFFFFFFF) >> $bits;
  }

  /**
   * 32-bit multiplication that avoids overflow on 64-bit PHP.
   * Equivalent to Math.imul() in JS.
   */
  private static function mul32(int $a, int $b): int
  {
    $a = $a & 0xFFFFFFFF;
    $b = $b & 0xFFFFFFFF;

    $ah = ($a >> 16) & 0xFFFF;
    $al = $a & 0xFFFF;
    $bh = ($b >> 16) & 0xFFFF;
    $bl = $b & 0xFFFF;

    return (($al * $bl) + ((($ah * $bl + $al * $bh) << 16) & 0xFFFFFFFF)) & 0xFFFFFFFF;
  }

  /**
   * Set default configuration options.
   *
   * @param array $options
   * @throws NaijaFakerException
   */
  public static function config(array $options): void
  {
    if (isset($options['language'])) {
      $lang = strtolower(trim($options['language']));
      if (!in_array($lang, self::VALID_LANGUAGES)) {
        throw new NaijaFakerException(
          'Invalid language. Use "yoruba", "igbo", or "hausa".',
          'INVALID_LANGUAGE'
        );
      }
      self::$language = $lang;
    }

    if (isset($options['gender'])) {
      $gen = strtolower(trim($options['gender']));
      if (!in_array($gen, self::VALID_GENDERS)) {
        throw new NaijaFakerException(
          'Invalid gender. Use "male" or "female".',
          'INVALID_GENDER'
        );
      }
      self::$gender = $gen;
    }

    if (isset($options['network'])) {
      $net = strtolower(trim($options['network']));
      if (!in_array($net, self::VALID_NETWORKS)) {
        throw new NaijaFakerException(
          'Invalid network. Use "mtn", "glo", "airtel", or "9mobile".',
          'INVALID_NETWORK'
        );
      }
      self::$network = $net;
    }
  }

  /**
   * Generates a fake Nigerian name.
   *
   * @param string|null $language
   * @param string|null $gender
   * @return string
   * @throws NaijaFakerException
   */
  public static function name(?string $language = null, ?string $gender = null): string
  {
    $language = self::resolveLanguage($language);
    $gender = self::resolveGender($gender);

    $loadLibrary = Library::getLibraryData($language);
    $firstName = self::_randomElement($loadLibrary[$gender]);
    $lastName = self::_randomElement($loadLibrary['male']);

    return "{$firstName} {$lastName}";
  }

  /**
   * Generates fake person data.
   *
   * @param string|null $language
   * @param string|null $gender
   * @return array
   */
  public static function person(?string $language = null, ?string $gender = null): array
  {
    $language = self::resolveLanguage($language);
    $gender = self::resolveGender($gender);

    $fullName = self::name($language, $gender);
    $splitName = explode(' ', $fullName);

    return [
      'title' => self::title($gender),
      'firstName' => $splitName[0],
      'lastName' => $splitName[1] ?? '',
      'fullName' => $fullName,
      'email' => self::email($fullName),
      'phone' => self::phoneNumber(),
      'address' => self::address(),
    ];
  }

  /**
   * Generates multiple fake people.
   *
   * @param int $count
   * @param string|null $language
   * @param string|null $gender
   * @return array
   * @throws NaijaFakerException
   */
  public static function people(int $count = 10, ?string $language = null, ?string $gender = null): array
  {
    self::validateCount($count);

    $people = [];
    for ($i = 0; $i < $count; $i++) {
      $people[] = self::person($language, $gender);
    }
    return $people;
  }

  /**
   * Generates a fake Nigerian title.
   *
   * @param string|null $gender
   * @return string
   */
  public static function title(?string $gender = null): string
  {
    $gender = self::resolveGender($gender);
    $loadLibrary = Library::getLibraryData('titles');
    return self::_randomElement($loadLibrary[$gender]);
  }

  /**
   * Generates a fake email address.
   *
   * @param string|null $name
   * @param string|null $extension
   * @return string
   */
  public static function email(?string $name = null, ?string $extension = null): string
  {
    $value = $name ? trim($name) : self::name();

    $loadLibrary = Library::getLibraryData('extensions');
    $domain = ($extension)
      ? '@' . preg_replace('/[^.a-zA-Z0-9]/', '', strtolower($extension))
      : self::_randomElement($loadLibrary);

    $separator = self::_randomElement(self::VALID_SEPARATORS);
    $value = preg_replace('/[^a-zA-Z0-9]+/i', $separator, strtolower($value));

    return "{$value}{$domain}";
  }

  /**
   * Generates a fake Nigerian address.
   *
   * @return string
   */
  public static function address(): string
  {
    $loadLibrary = Library::getLibraryData('address');

    $addressTypes = $loadLibrary['types'];
    $addressLocales = $loadLibrary['locale'];
    $addressLocale = self::_randomElement($addressLocales);
    $places = $loadLibrary['places'][$addressLocale];
    $states = $loadLibrary['states'][$addressLocale];
    $suffixes = $loadLibrary['suffixes'] ?? ['Plot', 'Km', 'No'];

    $number = self::_randomInt(1, 200);
    $addressType = self::_randomElement($addressTypes);

    // Use locale-matching name like JS does
    $langMap = ['east' => 'igbo', 'west' => 'yoruba', 'south' => 'igbo', 'north' => 'hausa'];
    $addressName = self::name($langMap[$addressLocale] ?? null);

    $place = self::_randomElement($places);
    $suffix = self::_randomElement($suffixes);

    return trim("{$suffix} {$number}, {$addressName} {$addressType}, {$place}");
  }

  /**
   * Generates a fake Nigerian phone number.
   *
   * @param string|null $network
   * @return string
   * @throws NaijaFakerException
   */
  public static function phoneNumber(?string $network = null): string
  {
    $network = $network ? strtolower(trim($network)) : (self::$network ?? null);

    if ($network && !in_array($network, self::VALID_NETWORKS)) {
      throw new NaijaFakerException(
        'Invalid network. Use "mtn", "glo", "airtel", or "9mobile".',
        'INVALID_NETWORK'
      );
    }

    if (!$network) {
      $network = self::_randomElement(self::VALID_NETWORKS);
    }

    $loadLibrary = Library::getLibraryData('numbers');
    $networkPrefixes = $loadLibrary['prefix'][$network];

    $selectedPrefix = self::_randomElement($networkPrefixes);
    $number = 1000000 + (int) floor(self::_random() * 9000000);
    $selectedPrefix = preg_replace('/^0+/', '+234', $selectedPrefix);

    return "{$selectedPrefix}{$number}";
  }

  /**
   * Returns all Nigerian states.
   *
   * @return array
   */
  public static function states(): array
  {
    return Library::getLibraryData('states');
  }

  /**
   * Returns all Nigerian LGAs.
   *
   * @return array
   */
  public static function lgas(): array
  {
    return Library::getLibraryData('lgas');
  }

  /**
   * Generates a fake BVN (Bank Verification Number).
   *
   * @return string 11-digit BVN
   */
  public static function bvn(): string
  {
    $bvn = '';
    for ($i = 0; $i < 11; $i++) {
      $bvn .= (int) floor(self::_random() * 10);
    }
    return $bvn;
  }

  /**
   * Generates a fake NIN (National Identification Number).
   *
   * @return string 11-digit NIN
   */
  public static function nin(): string
  {
    $nin = '';
    for ($i = 0; $i < 11; $i++) {
      $nin .= (int) floor(self::_random() * 10);
    }
    return $nin;
  }

  /**
   * Generates a fake Nigerian bank account.
   *
   * @param string|null $bankName
   * @return array {bankName, bankCode, accountNumber}
   * @throws NaijaFakerException
   */
  public static function bankAccount(?string $bankName = null): array
  {
    $banks = Library::getLibraryData('bank');

    if ($bankName) {
      $bank = null;
      foreach ($banks as $b) {
        if (strtolower($b['name']) === strtolower($bankName)) {
          $bank = $b;
          break;
        }
      }
      if (!$bank) {
        throw new NaijaFakerException(
          "Invalid bank name: \"{$bankName}\".",
          'INVALID_BANK'
        );
      }
    } else {
      $bank = self::_randomElement($banks);
    }

    $accountNumber = '';
    for ($i = 0; $i < 10; $i++) {
      $accountNumber .= (int) floor(self::_random() * 10);
    }

    return [
      'bankName' => $bank['name'],
      'bankCode' => $bank['code'],
      'accountNumber' => $accountNumber,
    ];
  }

  /**
   * Generates a geographically consistent fake Nigerian person.
   * Name ethnicity, state, and LGA all match.
   *
   * @param string|null $language
   * @param string|null $gender
   * @return array
   * @throws NaijaFakerException
   */
  public static function consistentPerson(?string $language = null, ?string $gender = null): array
  {
    $geo = Library::getLibraryData('geo');
    $regionMap = $geo['regionMap'];
    $stateLgas = $geo['stateLgas'];
    $languageToRegions = $geo['languageToRegions'];

    $lang = self::resolveLanguage($language, true);
    $gen = self::resolveGender($gender);

    $regions = $languageToRegions[$lang] ?? null;
    if (!$regions) {
      throw new NaijaFakerException(
        'Invalid language. Use "yoruba", "igbo", or "hausa".',
        'INVALID_LANGUAGE'
      );
    }

    $region = self::_randomElement($regions);
    $regionData = $regionMap[$region];
    $state = self::_randomElement($regionData['states']);
    $lgaList = $stateLgas[$state] ?? [];
    $lga = !empty($lgaList) ? self::_randomElement($lgaList) : null;

    // Build address from matching region
    $addressData = Library::getLibraryData('address');
    $addressPlaces = $addressData['places'][$region] ?? [];
    $addressType = self::_randomElement($addressData['types']);
    $suffixes = $addressData['suffixes'] ?? ['Plot', 'Km', 'No'];
    $addressSuffix = self::_randomElement($suffixes);
    $addressNumber = self::_randomInt(1, 200);
    $addressName = self::name($lang);
    $addressPlace = !empty($addressPlaces) ? self::_randomElement($addressPlaces) : $state;
    $fullAddress = trim("{$addressSuffix} {$addressNumber}, {$addressName} {$addressType}, {$addressPlace}");

    $fullName = self::name($lang, $gen);
    $splitName = explode(' ', $fullName);

    return [
      'title' => self::title($gen),
      'firstName' => $splitName[0],
      'lastName' => $splitName[1] ?? '',
      'fullName' => $fullName,
      'email' => self::email($fullName),
      'phone' => self::phoneNumber(),
      'address' => $fullAddress,
      'state' => $state,
      'lga' => $lga,
    ];
  }

  /**
   * Generates multiple geographically consistent fake people.
   *
   * @param int $count
   * @param string|null $language
   * @param string|null $gender
   * @return array
   * @throws NaijaFakerException
   */
  public static function consistentPeople(int $count = 10, ?string $language = null, ?string $gender = null): array
  {
    self::validateCount($count);

    $list = [];
    for ($i = 0; $i < $count; $i++) {
      $list[] = self::consistentPerson($language, $gender);
    }
    return $list;
  }

  /**
   * Generates a fake Nigerian license plate.
   *
   * @param string|null $state
   * @return string e.g. "LAG-234XY"
   * @throws NaijaFakerException
   */
  public static function licensePlate(?string $state = null): string
  {
    $stateCodes = Library::getLibraryData('plates');
    $stateNames = array_keys($stateCodes);

    if ($state) {
      $selectedState = null;
      foreach ($stateNames as $name) {
        if (strtolower($name) === strtolower($state)) {
          $selectedState = $name;
          break;
        }
      }
      if (!$selectedState) {
        throw new NaijaFakerException(
          "Invalid state name: \"{$state}\".",
          'INVALID_STATE'
        );
      }
    } else {
      $selectedState = self::_randomElement($stateNames);
    }

    $code = $stateCodes[$selectedState];
    $digits = str_pad((string) (100 + (int) floor(self::_random() * 900)), 3, '0', STR_PAD_LEFT);
    $letters = 'ABCDEFGHJKLMNPRSTUVWXYZ';
    $letter1 = $letters[(int) floor(self::_random() * strlen($letters))];
    $letter2 = $letters[(int) floor(self::_random() * strlen($letters))];

    return "{$code}-{$digits}{$letter1}{$letter2}";
  }

  /**
   * Generates a fake Nigerian company.
   *
   * @return array {name, rcNumber, industry}
   */
  public static function company(): array
  {
    $data = Library::getLibraryData('company');

    $prefix = self::_randomElement($data['prefixes']);
    $noun = self::_randomElement($data['nouns']);
    $suffix = self::_randomElement($data['suffixes']);
    $industry = self::_randomElement($data['industries']);
    $rcNum = 100000 + (int) floor(self::_random() * 9900000);

    return [
      'name' => "{$prefix} {$noun} {$suffix}",
      'rcNumber' => "RC-{$rcNum}",
      'industry' => $industry,
    ];
  }

  /**
   * Generates a random Nigerian university.
   *
   * @return array {name, abbreviation, state, type}
   */
  public static function university(): array
  {
    $universities = Library::getLibraryData('university');
    $uni = self::_randomElement($universities);
    return [
      'name' => $uni['name'],
      'abbreviation' => $uni['abbreviation'],
      'state' => $uni['state'],
      'type' => $uni['type'],
    ];
  }

  /**
   * Generates a fake education record.
   *
   * @param string|null $language
   * @return array {university, abbreviation, degree, course, graduationYear}
   */
  public static function educationRecord(?string $language = null): array
  {
    $universities = Library::getLibraryData('university');
    $jobsData = Library::getLibraryData('jobs');
    $uni = null;

    if ($language) {
      $geo = Library::getLibraryData('geo');
      $regions = $geo['languageToRegions'][strtolower($language)] ?? null;
      if ($regions) {
        $regionStates = [];
        foreach ($regions as $r) {
          $regionStates = array_merge($regionStates, $geo['regionMap'][$r]['states']);
        }
        $regionalUnis = array_filter($universities, function ($u) use ($regionStates) {
          return in_array($u['state'], $regionStates);
        });
        if (!empty($regionalUnis)) {
          $uni = self::_randomElement(array_values($regionalUnis));
        }
      }
    }

    if (!$uni) {
      $uni = self::_randomElement($universities);
    }

    $degree = self::_randomElement($jobsData['degrees']);
    $course = self::_randomElement($jobsData['courses']);
    $currentYear = (int) date('Y');
    $graduationYear = $currentYear - (int) floor(self::_random() * 20) - 1;

    return [
      'university' => $uni['name'],
      'abbreviation' => $uni['abbreviation'],
      'degree' => $degree['code'],
      'course' => $course,
      'graduationYear' => $graduationYear,
    ];
  }

  /**
   * Generates a fake work/employment record.
   *
   * @return array {company, position, industry, startYear}
   */
  public static function workRecord(): array
  {
    $comp = self::company();
    $jobsData = Library::getLibraryData('jobs');
    $position = self::_randomElement($jobsData['positions']);
    $currentYear = (int) date('Y');
    $startYear = $currentYear - (int) floor(self::_random() * 15);

    return [
      'company' => $comp['name'],
      'position' => $position,
      'industry' => $comp['industry'],
      'startYear' => $startYear,
    ];
  }

  /**
   * Generates a fake vehicle record.
   *
   * @param string|null $state
   * @return array {licensePlate, make, model, year, color}
   */
  public static function vehicleRecord(?string $state = null): array
  {
    $plate = self::licensePlate($state);
    $vehicleData = Library::getLibraryData('vehicles');
    $makeData = self::_randomElement($vehicleData['makes']);
    $model = self::_randomElement($makeData['models']);
    $color = self::_randomElement($vehicleData['colors']);
    $currentYear = (int) date('Y');
    $year = $currentYear - (int) floor(self::_random() * 15);

    return [
      'licensePlate' => $plate,
      'make' => $makeData['make'],
      'model' => $model,
      'year' => $year,
      'color' => $color,
    ];
  }

  /**
   * Generates a fake date of birth with age.
   *
   * @param array|null $options {minAge: int, maxAge: int}
   * @return array {date: string, age: int}
   * @throws NaijaFakerException
   */
  public static function dateOfBirth(?array $options = null): array
  {
    $minAge = $options['minAge'] ?? 18;
    $maxAge = $options['maxAge'] ?? 65;

    if (!is_numeric($minAge) || !is_numeric($maxAge)) {
      throw new NaijaFakerException(
        'minAge and maxAge must be numbers.',
        'INVALID_PARAM'
      );
    }

    $minAge = (int) $minAge;
    $maxAge = (int) $maxAge;

    if ($minAge < 0 || $maxAge < 0) {
      throw new NaijaFakerException(
        'minAge and maxAge must not be negative.',
        'INVALID_PARAM'
      );
    }

    if ($minAge > $maxAge) {
      throw new NaijaFakerException(
        'minAge must be less than or equal to maxAge.',
        'INVALID_PARAM'
      );
    }

    $age = $minAge + (int) floor(self::_random() * ($maxAge - $minAge + 1));

    $now = new \DateTime();
    $birthYear = (int) $now->format('Y') - $age;
    $month = (int) floor(self::_random() * 12) + 1;
    $day = (int) floor(self::_random() * 28) + 1;

    $date = sprintf('%04d-%02d-%02d', $birthYear, $month, $day);

    return [
      'date' => $date,
      'age' => $age,
    ];
  }

  /**
   * Generates a random marital status.
   *
   * @return string
   */
  public static function maritalStatus(): string
  {
    $statuses = ['Single', 'Married', 'Divorced', 'Widowed', 'Separated'];
    return self::_randomElement($statuses);
  }

  /**
   * Generates a random blood group.
   *
   * @return string e.g. "O+"
   */
  public static function bloodGroup(): string
  {
    $data = Library::getLibraryData('medical');
    return self::_randomElement($data['bloodGroups']);
  }

  /**
   * Generates a random genotype.
   *
   * @return string e.g. "AA", "AS"
   */
  public static function genotype(): string
  {
    $data = Library::getLibraryData('medical');
    return self::_randomElement($data['genotypes']);
  }

  /**
   * Generates a fake salary.
   *
   * @param array|null $options {level: string}
   * @return array {amount, currency, level, frequency}
   * @throws NaijaFakerException
   */
  public static function salary(?array $options = null): array
  {
    $salaryData = Library::getLibraryData('salary');

    $level = isset($options['level'])
      ? strtolower($options['level'])
      : self::_randomElement($salaryData['levels']);

    $band = $salaryData['bands'][$level] ?? null;
    if (!$band) {
      throw new NaijaFakerException(
        'Invalid level. Use "entry", "mid", "senior", or "executive".',
        'INVALID_LEVEL'
      );
    }

    $amount = (int) floor($band['min'] + self::_random() * ($band['max'] - $band['min']));
    $rounded = (int) round($amount / 1000) * 1000;

    return [
      'amount' => $rounded,
      'currency' => 'NGN',
      'level' => $level,
      'frequency' => 'monthly',
    ];
  }

  /**
   * Generates a fake next of kin.
   *
   * @param string|null $language
   * @param string|null $gender Gender of the kin
   * @return array {fullName, relationship, phone, address}
   */
  public static function nextOfKin(?string $language = null, ?string $gender = null): array
  {
    $maleRelationships = ['Father', 'Brother', 'Spouse', 'Uncle', 'Son'];
    $femaleRelationships = ['Mother', 'Sister', 'Spouse', 'Aunt', 'Daughter'];

    $gen = $gender ? strtolower(trim($gender)) : self::_randomElement(self::VALID_GENDERS);

    $relationships = ($gen === 'female') ? $femaleRelationships : $maleRelationships;
    $relationship = self::_randomElement($relationships);
    $fullName = self::name($language, $gen);

    return [
      'fullName' => $fullName,
      'relationship' => $relationship,
      'phone' => self::phoneNumber(),
      'address' => self::address(),
    ];
  }

  /**
   * Generates a detailed fake Nigerian person with all data.
   *
   * @param string|null $language
   * @param string|null $gender
   * @return array
   */
  public static function detailedPerson(?string $language = null, ?string $gender = null): array
  {
    $person = self::consistentPerson($language, $gender);

    $lang = $language ? strtolower(trim($language))
      : (self::$language ?? self::_randomElement(self::VALID_LANGUAGES));

    $education = self::educationRecord($lang);
    $work = self::workRecord();
    $vehicle = self::vehicleRecord($person['state']);

    $personGender = $gender ? strtolower(trim($gender)) : null;
    $kinGender = ($personGender === 'male') ? 'female'
      : (($personGender === 'female') ? 'male' : null);

    return array_merge($person, [
      'dateOfBirth' => self::dateOfBirth(),
      'maritalStatus' => self::maritalStatus(),
      'bloodGroup' => self::bloodGroup(),
      'genotype' => self::genotype(),
      'salary' => self::salary(),
      'nextOfKin' => self::nextOfKin($lang, $kinGender),
      'education' => $education,
      'work' => $work,
      'vehicle' => $vehicle,
    ]);
  }

  /**
   * Generates multiple detailed fake Nigerian persons.
   *
   * @param int $count
   * @param string|null $language
   * @param string|null $gender
   * @return array
   * @throws NaijaFakerException
   */
  public static function detailedPeople(int $count = 10, ?string $language = null, ?string $gender = null): array
  {
    self::validateCount($count);

    $list = [];
    for ($i = 0; $i < $count; $i++) {
      $list[] = self::detailedPerson($language, $gender);
    }
    return $list;
  }

  /**
   * Exports generated data as JSON or CSV string.
   *
   * @param string $type person|detailedPerson|consistentPerson
   * @param int $count
   * @param string $format json|csv
   * @return string
   * @throws NaijaFakerException
   */
  public static function export(string $type = 'person', int $count = 10, string $format = 'json'): string
  {
    self::validateCount($count);

    $format = strtolower($format);
    if (!in_array($format, ['json', 'csv'])) {
      throw new NaijaFakerException(
        'Invalid format. Use "json" or "csv".',
        'INVALID_PARAM'
      );
    }

    $generators = [
      'person' => fn() => self::people($count),
      'detailedperson' => fn() => self::detailedPeople($count),
      'consistentperson' => fn() => self::consistentPeople($count),
    ];

    $generatorKey = strtolower($type);
    $generator = $generators[$generatorKey] ?? null;

    if (!$generator) {
      throw new NaijaFakerException(
        'Invalid type. Use "person", "detailedPerson", or "consistentPerson".',
        'INVALID_TYPE'
      );
    }

    $data = $generator();

    if ($format === 'csv') {
      return self::toCSV($data);
    }

    return json_encode($data, JSON_PRETTY_PRINT);
  }

  /**
   * Converts array of associative arrays to CSV string.
   *
   * @param array $data
   * @return string
   */
  private static function toCSV(array $data): string
  {
    if (empty($data)) return '';

    $flatData = array_map(function ($item) {
      return self::flattenArray($item);
    }, $data);

    $headers = array_keys($flatData[0]);
    $rows = array_map(function ($item) use ($headers) {
      return implode(',', array_map(function ($h) use ($item) {
        $val = isset($item[$h]) ? (string) $item[$h] : '';
        return str_contains($val, ',') ? "\"{$val}\"" : $val;
      }, $headers));
    }, $flatData);

    return implode("\n", array_merge([implode(',', $headers)], $rows));
  }

  /**
   * Flattens nested arrays with dot notation keys.
   */
  private static function flattenArray(array $array, string $prefix = ''): array
  {
    $result = [];
    foreach ($array as $key => $value) {
      $newKey = $prefix ? "{$prefix}.{$key}" : $key;
      if (is_array($value) && !array_is_list($value)) {
        $result = array_merge($result, self::flattenArray($value, $newKey));
      } else {
        $result[$newKey] = $value;
      }
    }
    return $result;
  }

  /**
   * Register a custom data provider.
   *
   * @param string $name
   * @param callable $handler
   * @throws NaijaFakerException
   */
  public static function registerProvider(string $name, callable $handler): void
  {
    if (empty(trim($name))) {
      throw new NaijaFakerException(
        'Provider name must be a non-empty string.',
        'INVALID_PARAM'
      );
    }

    $key = strtolower($name);

    // Prevent overriding built-in methods
    if (method_exists(static::class, $key) || method_exists(static::class, $name)) {
      throw new NaijaFakerException(
        "Cannot override built-in method: \"{$name}\".",
        'INVALID_PARAM'
      );
    }

    self::$customProviders[$key] = $handler;
  }

  /**
   * Generate a value from a registered custom provider.
   *
   * @param string $name
   * @return mixed
   * @throws NaijaFakerException
   */
  public static function generate(string $name)
  {
    if (empty(trim($name))) {
      throw new NaijaFakerException(
        'Provider name must be a non-empty string.',
        'INVALID_PARAM'
      );
    }

    $key = strtolower($name);
    $handler = self::$customProviders[$key] ?? null;

    if (!$handler) {
      throw new NaijaFakerException(
        "Unknown provider: \"{$name}\". Register it first with registerProvider().",
        'INVALID_PARAM'
      );
    }

    return $handler(new class {
      public function random(): float
      {
        return NaijaFaker::_random();
      }
    });
  }

  /**
   * List all registered custom provider names.
   *
   * @return array
   */
  public static function listProviders(): array
  {
    return array_keys(self::$customProviders);
  }

  /**
   * Resolves and validates language parameter.
   *
   * @param string|null $language
   * @param bool $strict If true, throw on invalid language instead of picking random
   * @return string
   * @throws NaijaFakerException
   */
  private static function resolveLanguage(?string $language, bool $strict = false): string
  {
    if ($language) {
      $lang = strtolower(trim($language));
      if (in_array($lang, self::VALID_LANGUAGES)) {
        return $lang;
      }
      if ($strict) {
        throw new NaijaFakerException(
          'Invalid language. Use "yoruba", "igbo", or "hausa".',
          'INVALID_LANGUAGE'
        );
      }
    }

    if (self::$language) {
      return self::$language;
    }

    return self::_randomElement(self::VALID_LANGUAGES);
  }

  /**
   * Resolves gender parameter.
   */
  private static function resolveGender(?string $gender): string
  {
    if ($gender) {
      $gen = strtolower(trim($gender));
      if (in_array($gen, self::VALID_GENDERS)) {
        return $gen;
      }
    }

    if (self::$gender) {
      return self::$gender;
    }

    return self::_randomElement(self::VALID_GENDERS);
  }

  /**
   * Validates that count is a positive integer.
   *
   * @throws NaijaFakerException
   */
  private static function validateCount(int $count): void
  {
    if ($count < 1) {
      throw new NaijaFakerException(
        'Count must be a positive integer.',
        'INVALID_PARAM'
      );
    }
  }
}
