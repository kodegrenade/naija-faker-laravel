<?php

use Kodegrenade\NaijaFaker\NaijaFaker;

test('address returns non-empty string', function () {
  $address = NaijaFaker::address();
  expect($address)->toBeString()->not->toBeEmpty();
});

test('address contains comma separator', function () {
  $address = NaijaFaker::address();
  expect($address)->toContain(',');
});
