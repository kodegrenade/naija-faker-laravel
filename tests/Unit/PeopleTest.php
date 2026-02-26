<?php

use Kodegrenade\NaijaFaker\NaijaFaker;
use Kodegrenade\NaijaFaker\Exceptions\NaijaFakerException;

test('people without params returns array of 10', function () {
  $people = NaijaFaker::people();
  expect($people)->toBeArray()->toHaveCount(10);
});

test('people with params returns correct count', function () {
  $people = NaijaFaker::people(3, 'igbo', 'female');
  expect($people)->toBeArray()->toHaveCount(3);
});

test('each person in people has correct keys', function () {
  $people = NaijaFaker::people(2);
  foreach ($people as $person) {
    expect($person)->toHaveKeys(['title', 'firstName', 'lastName', 'fullName', 'email', 'phone', 'address']);
  }
});

test('people throws on zero count', function () {
  NaijaFaker::people(0);
})->throws(NaijaFakerException::class);

test('people throws on negative count', function () {
  NaijaFaker::people(-1);
})->throws(NaijaFakerException::class);
