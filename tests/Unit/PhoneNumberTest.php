<?php

use Kodegrenade\NaijaFaker\NaijaFaker;

test('phone number without params', function () {
  $phoneNumber = NaijaFaker::phoneNumber();
  expect($phoneNumber)->toBeString();
});

test('phone number with params', function () {
  $phoneNumber = NaijaFaker::phoneNumber('mtn');
  expect($phoneNumber)->toBeString();
});
