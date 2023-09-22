<?php

use Kodegrenade\NaijaFaker\NaijaFaker;

test('all lgas are returned', function () {
  $lgas = NaijaFaker::lgas();
  expect($lgas)->toBeArray();
});
