<?php

use Kodegrenade\NaijaFaker\NaijaFaker;

test('email with name returns string', function () {
  $email = NaijaFaker::email('John Doe');
  expect($email)->toBeString()->not->toBeEmpty();
});

test('email without name returns string', function () {
  $email = NaijaFaker::email();
  expect($email)->toBeString()->not->toBeEmpty();
});

test('email with custom extension', function () {
  $email = NaijaFaker::email('John Doe', 'company.com');
  expect($email)->toBeString()->toContain('@company.com');
});
