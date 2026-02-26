<?php

use Kodegrenade\NaijaFaker\NaijaFaker;

test('person without params returns array with correct keys', function () {
  $person = NaijaFaker::person();
  expect($person)->toBeArray()
    ->toHaveKeys(['title', 'firstName', 'lastName', 'fullName', 'email', 'phone', 'address']);
});

test('person with params returns array with correct keys', function () {
  $person = NaijaFaker::person('hausa', 'female');
  expect($person)->toBeArray()
    ->toHaveKeys(['title', 'firstName', 'lastName', 'fullName', 'email', 'phone', 'address']);
});

test('person fullName contains space', function () {
  $person = NaijaFaker::person();
  expect($person['fullName'])->toContain(' ');
});

test('person phone starts with +234', function () {
  $person = NaijaFaker::person();
  expect($person['phone'])->toStartWith('+234');
});
