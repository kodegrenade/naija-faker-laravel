<?php

use Kodegrenade\NaijaFaker\NaijaFaker;
use Kodegrenade\NaijaFaker\Exceptions\NaijaFakerException;

# CORE METHODS

test('name without params', function () {
  $name = NaijaFaker::name();
  expect($name)->toBeString()->toContain(' ');
});

test('name with language and gender', function () {
  $name = NaijaFaker::name('yoruba', 'male');
  expect($name)->toBeString()->toContain(' ');
});

test('name with all three languages', function () {
  foreach (['yoruba', 'igbo', 'hausa'] as $lang) {
    $name = NaijaFaker::name($lang);
    expect($name)->toBeString()->toContain(' ');
  }
});

test('person returns correct shape', function () {
  $person = NaijaFaker::person();
  expect($person)->toBeArray()
    ->toHaveKeys(['title', 'firstName', 'lastName', 'fullName', 'email', 'phone', 'address']);
  expect($person['fullName'])->toContain(' ');
  expect($person['email'])->toBeString()->not->toBeEmpty();
  expect($person['phone'])->toStartWith('+234');
});

test('person with params', function () {
  $person = NaijaFaker::person('hausa', 'female');
  expect($person)->toBeArray()
    ->toHaveKeys(['title', 'firstName', 'lastName', 'fullName']);
});

test('people returns correct count', function () {
  $people = NaijaFaker::people(3);
  expect($people)->toBeArray()->toHaveCount(3);
  expect($people[0])->toHaveKeys(['firstName', 'lastName', 'fullName']);
});

test('people default count is 10', function () {
  $people = NaijaFaker::people();
  expect($people)->toHaveCount(10);
});

test('title returns string', function () {
  $title = NaijaFaker::title();
  expect($title)->toBeString();
});

test('title with gender', function () {
  $title = NaijaFaker::title('male');
  expect($title)->toBeString();
});

test('email with name', function () {
  $email = NaijaFaker::email('John Doe');
  expect($email)->toBeString();
});

test('email without name', function () {
  $email = NaijaFaker::email();
  expect($email)->toBeString();
});

test('address returns string', function () {
  $address = NaijaFaker::address();
  expect($address)->toBeString()->not->toBeEmpty();
});

test('phone number without params', function () {
  $phone = NaijaFaker::phoneNumber();
  expect($phone)->toBeString()->toStartWith('+234');
});

test('phone number with network', function () {
  $phone = NaijaFaker::phoneNumber('mtn');
  expect($phone)->toBeString()->toStartWith('+234');
});

test('states returns array', function () {
  $states = NaijaFaker::states();
  expect($states)->toBeArray()->not->toBeEmpty();
});

test('lgas returns array', function () {
  $lgas = NaijaFaker::lgas();
  expect($lgas)->toBeArray()->not->toBeEmpty();
});

# IDENTITY & FINANCIAL

test('bvn returns 11-digit string', function () {
  $bvn = NaijaFaker::bvn();
  expect($bvn)->toBeString()->toHaveLength(11);
  expect(ctype_digit($bvn))->toBeTrue();
});

test('nin returns 11-digit string', function () {
  $nin = NaijaFaker::nin();
  expect($nin)->toBeString()->toHaveLength(11);
  expect(ctype_digit($nin))->toBeTrue();
});

test('bankAccount returns correct shape', function () {
  $account = NaijaFaker::bankAccount();
  expect($account)->toBeArray()
    ->toHaveKeys(['bankName', 'bankCode', 'accountNumber']);
  expect($account['accountNumber'])->toHaveLength(10);
  expect(ctype_digit($account['accountNumber']))->toBeTrue();
});

test('bankAccount with valid bank name', function () {
  $account = NaijaFaker::bankAccount('Access Bank');
  expect($account['bankName'])->toBe('Access Bank');
  expect($account['bankCode'])->toBe('044');
});

test('bankAccount throws on invalid bank', function () {
  NaijaFaker::bankAccount('Invalid Bank');
})->throws(NaijaFakerException::class);

# GEOGRAPHIC CONSISTENCY

test('consistentPerson returns correct shape', function () {
  $person = NaijaFaker::consistentPerson();
  expect($person)->toBeArray()
    ->toHaveKeys(['title', 'firstName', 'lastName', 'fullName', 'email', 'phone', 'address', 'state', 'lga']);
  expect($person['state'])->toBeString()->not->toBeEmpty();
});

test('consistentPerson with yoruba has western state', function () {
  NaijaFaker::seed(42);
  $westernStates = ['Lagos', 'Oyo', 'Osun', 'Ogun', 'Ondo', 'Ekiti', 'Kwara'];
  $found = false;
  for ($i = 0; $i < 20; $i++) {
    $person = NaijaFaker::consistentPerson('yoruba');
    if (in_array($person['state'], $westernStates)) {
      $found = true;
      break;
    }
  }
  expect($found)->toBeTrue();
  NaijaFaker::seed(null);
});

test('consistentPerson state has matching LGA', function () {
  $geo = Kodegrenade\NaijaFaker\Library\Library::getLibraryData('geo');
  $person = NaijaFaker::consistentPerson();
  $stateLgas = $geo['stateLgas'][$person['state']] ?? [];
  if ($person['lga'] !== null) {
    expect($stateLgas)->toContain($person['lga']);
  }
});

test('consistentPeople returns correct count', function () {
  $people = NaijaFaker::consistentPeople(5);
  expect($people)->toBeArray()->toHaveCount(5);
  expect($people[0])->toHaveKeys(['state', 'lga']);
});

# RECORDS

test('licensePlate matches format', function () {
  $plate = NaijaFaker::licensePlate();
  expect($plate)->toBeString()->toMatch('/^[A-Z]{2,3}-\d{3}[A-Z]{2}$/');
});

test('licensePlate with valid state', function () {
  $plate = NaijaFaker::licensePlate('Lagos');
  expect($plate)->toStartWith('LAG-');
});

test('licensePlate throws on invalid state', function () {
  NaijaFaker::licensePlate('InvalidState');
})->throws(NaijaFakerException::class);

test('company returns correct shape', function () {
  $company = NaijaFaker::company();
  expect($company)->toBeArray()
    ->toHaveKeys(['name', 'rcNumber', 'industry']);
  expect($company['rcNumber'])->toStartWith('RC-');
});

test('university returns correct shape', function () {
  $uni = NaijaFaker::university();
  expect($uni)->toBeArray()
    ->toHaveKeys(['name', 'abbreviation', 'state', 'type']);
  expect($uni['type'])->toBeIn(['federal', 'state', 'private']);
});

test('educationRecord returns correct shape', function () {
  $record = NaijaFaker::educationRecord();
  expect($record)->toBeArray()
    ->toHaveKeys(['university', 'abbreviation', 'degree', 'course', 'graduationYear']);
  expect($record['graduationYear'])->toBeInt()->toBeLessThanOrEqual((int) date('Y'));
});

test('workRecord returns correct shape', function () {
  $record = NaijaFaker::workRecord();
  expect($record)->toBeArray()
    ->toHaveKeys(['company', 'position', 'industry', 'startYear']);
  expect($record['startYear'])->toBeInt()->toBeLessThanOrEqual((int) date('Y'));
});

test('vehicleRecord returns correct shape', function () {
  $record = NaijaFaker::vehicleRecord();
  expect($record)->toBeArray()
    ->toHaveKeys(['licensePlate', 'make', 'model', 'year', 'color']);
  expect($record['licensePlate'])->toMatch('/^[A-Z]{2,3}-\d{3}[A-Z]{2}$/');
});

# PERSONAL DATA

test('dateOfBirth returns correct shape', function () {
  $dob = NaijaFaker::dateOfBirth();
  expect($dob)->toBeArray()->toHaveKeys(['date', 'age']);
  expect($dob['date'])->toMatch('/^\d{4}-\d{2}-\d{2}$/');
  expect($dob['age'])->toBeInt()->toBeGreaterThanOrEqual(18)->toBeLessThanOrEqual(65);
});

test('dateOfBirth respects custom age range', function () {
  $dob = NaijaFaker::dateOfBirth(['minAge' => 25, 'maxAge' => 30]);
  expect($dob['age'])->toBeGreaterThanOrEqual(25)->toBeLessThanOrEqual(30);
});

test('dateOfBirth throws on negative age', function () {
  NaijaFaker::dateOfBirth(['minAge' => -5, 'maxAge' => 30]);
})->throws(NaijaFakerException::class);

test('dateOfBirth throws on swapped ages', function () {
  NaijaFaker::dateOfBirth(['minAge' => 65, 'maxAge' => 18]);
})->throws(NaijaFakerException::class);

test('maritalStatus returns valid status', function () {
  $status = NaijaFaker::maritalStatus();
  expect($status)->toBeIn(['Single', 'Married', 'Divorced', 'Widowed', 'Separated']);
});

test('bloodGroup returns valid blood group', function () {
  $bg = NaijaFaker::bloodGroup();
  expect($bg)->toBeIn(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
});

test('genotype returns valid genotype', function () {
  $gt = NaijaFaker::genotype();
  expect($gt)->toBeIn(['AA', 'AS', 'AC', 'SS', 'SC']);
});

test('salary returns correct shape', function () {
  $salary = NaijaFaker::salary();
  expect($salary)->toBeArray()
    ->toHaveKeys(['amount', 'currency', 'level', 'frequency']);
  expect($salary['currency'])->toBe('NGN');
  expect($salary['frequency'])->toBe('monthly');
  expect($salary['level'])->toBeIn(['entry', 'mid', 'senior', 'executive']);
  expect($salary['amount'])->toBeInt();
  expect($salary['amount'] % 1000)->toBe(0);
});

test('salary with specific level', function () {
  $salary = NaijaFaker::salary(['level' => 'entry']);
  expect($salary['level'])->toBe('entry');
  expect($salary['amount'])->toBeGreaterThanOrEqual(50000)->toBeLessThanOrEqual(150000);
});

test('salary throws on invalid level', function () {
  NaijaFaker::salary(['level' => 'ceo']);
})->throws(NaijaFakerException::class);

test('nextOfKin returns correct shape', function () {
  $kin = NaijaFaker::nextOfKin();
  expect($kin)->toBeArray()
    ->toHaveKeys(['fullName', 'relationship', 'phone', 'address']);
  expect($kin['phone'])->toStartWith('+234');
});

# COMPOSITE METHODS

test('detailedPerson returns all fields', function () {
  $person = NaijaFaker::detailedPerson();
  expect($person)->toBeArray()
    ->toHaveKeys([
      'title',
      'firstName',
      'lastName',
      'fullName',
      'email',
      'phone',
      'address',
      'state',
      'lga',
      'dateOfBirth',
      'maritalStatus',
      'bloodGroup',
      'genotype',
      'salary',
      'nextOfKin',
      'education',
      'work',
      'vehicle',
    ]);
  expect($person['education'])->toHaveKeys(['university', 'abbreviation', 'degree', 'course', 'graduationYear']);
  expect($person['work'])->toHaveKeys(['company', 'position', 'industry', 'startYear']);
  expect($person['vehicle'])->toHaveKeys(['licensePlate', 'make', 'model', 'year', 'color']);
  expect($person['salary'])->toHaveKeys(['amount', 'currency', 'level', 'frequency']);
  expect($person['nextOfKin'])->toHaveKeys(['fullName', 'relationship', 'phone', 'address']);
  expect($person['dateOfBirth'])->toHaveKeys(['date', 'age']);
});

test('detailedPeople returns correct count', function () {
  $people = NaijaFaker::detailedPeople(3);
  expect($people)->toBeArray()->toHaveCount(3);
  expect($people[0])->toHaveKey('education');
});

# SEEDED PRNG

test('seeded output is deterministic', function () {
  NaijaFaker::seed(12345);
  $name1 = NaijaFaker::name();
  $bvn1 = NaijaFaker::bvn();

  NaijaFaker::seed(12345);
  $name2 = NaijaFaker::name();
  $bvn2 = NaijaFaker::bvn();

  expect($name1)->toBe($name2);
  expect($bvn1)->toBe($bvn2);

  NaijaFaker::seed(null);
});

test('different seeds produce different output', function () {
  NaijaFaker::seed(111);
  $name1 = NaijaFaker::name();

  NaijaFaker::seed(999);
  $name2 = NaijaFaker::name();

  expect($name1)->not->toBe($name2);

  NaijaFaker::seed(null);
});

test('seed reset returns to non-deterministic mode', function () {
  NaijaFaker::seed(42);
  NaijaFaker::seed(null);
  // Should not throw and should work normally
  $name = NaijaFaker::name();
  expect($name)->toBeString();
});

# EXPORT

test('export json returns valid JSON', function () {
  $json = NaijaFaker::export('person', 3, 'json');
  $data = json_decode($json, true);
  expect($data)->toBeArray()->toHaveCount(3);
  expect($data[0])->toHaveKeys(['firstName', 'lastName']);
});

test('export csv has correct structure', function () {
  $csv = NaijaFaker::export('person', 3, 'csv');
  $lines = explode("\n", $csv);
  expect(count($lines))->toBe(4); // header + 3 data rows
  expect($lines[0])->toContain('firstName');
});

test('export throws on invalid type', function () {
  NaijaFaker::export('invalid', 3);
})->throws(NaijaFakerException::class);

test('export throws on invalid format', function () {
  NaijaFaker::export('person', 3, 'xml');
})->throws(NaijaFakerException::class);

# CUSTOM PROVIDERS

test('custom provider register and generate', function () {
  NaijaFaker::registerProvider('religion', function ($faker) {
    $religions = ['Christianity', 'Islam', 'Traditional'];
    return $religions[array_rand($religions)];
  });
  $result = NaijaFaker::generate('religion');
  expect($result)->toBeIn(['Christianity', 'Islam', 'Traditional']);
});

test('custom provider is case insensitive', function () {
  NaijaFaker::registerProvider('testprovider', function ($faker) {
    return 'test_value';
  });
  $result = NaijaFaker::generate('TESTPROVIDER');
  expect($result)->toBe('test_value');
});

test('listProviders returns registered names', function () {
  $providers = NaijaFaker::listProviders();
  expect($providers)->toBeArray()->toContain('religion');
});

test('custom provider throws on built-in override', function () {
  NaijaFaker::registerProvider('name', function () {
    return 'test';
  });
})->throws(NaijaFakerException::class);

test('generate throws on unregistered provider', function () {
  NaijaFaker::generate('nonexistent');
})->throws(NaijaFakerException::class);

# ERROR HANDLING

test('phoneNumber throws on invalid network', function () {
  NaijaFaker::phoneNumber('vodafone');
})->throws(NaijaFakerException::class);

test('people throws on zero count', function () {
  NaijaFaker::people(0);
})->throws(NaijaFakerException::class);

test('people throws on negative count', function () {
  NaijaFaker::people(-5);
})->throws(NaijaFakerException::class);

test('config throws on invalid language', function () {
  NaijaFaker::config(['language' => 'french']);
})->throws(NaijaFakerException::class);

test('config throws on invalid gender', function () {
  NaijaFaker::config(['gender' => 'other']);
})->throws(NaijaFakerException::class);

test('config throws on invalid network', function () {
  NaijaFaker::config(['network' => 'vodafone']);
})->throws(NaijaFakerException::class);
