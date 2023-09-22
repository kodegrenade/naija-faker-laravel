<?php

use Kodegrenade\NaijaFaker\NaijaFaker;

test('all states are returned', function () {
  $states = NaijaFaker::states();
  expect($states)->toBeArray();
});
