<?php

use Kodegrenade\NaijaFaker\NaijaFaker;
use Kodegrenade\NaijaFaker\Exceptions\NaijaFakerException;

test('phone number without params starts with +234', function () {
  $phoneNumber = NaijaFaker::phoneNumber();
  expect($phoneNumber)->toBeString()->toStartWith('+234');
});

test('phone number with valid network', function () {
  foreach (['mtn', 'glo', 'airtel', '9mobile'] as $network) {
    $phone = NaijaFaker::phoneNumber($network);
    expect($phone)->toBeString()->toStartWith('+234');
  }
});

test('phone number throws on invalid network', function () {
  NaijaFaker::phoneNumber('vodafone');
})->throws(NaijaFakerException::class);
