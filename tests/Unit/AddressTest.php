<?php

use Kodegrenade\NaijaFaker\NaijaFaker;

test('address', function () {
  $address = NaijaFaker::address();
  expect($address)->toBeString();
});
