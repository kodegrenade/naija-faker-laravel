<?php

use Kodegrenade\NaijaFaker\NaijaFaker;

test('person without params', function () {
  $person = NaijaFaker::person();
  expect($person)->toBeObject();
});

test('person with params', function () {
  $person = NaijaFaker::person('hausa', 'female');
  expect($person)->toBeObject();
});
