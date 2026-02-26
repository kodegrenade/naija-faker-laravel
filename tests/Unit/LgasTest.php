<?php

use Kodegrenade\NaijaFaker\NaijaFaker;

test('lgas returns non-empty array', function () {
  $lgas = NaijaFaker::lgas();
  expect($lgas)->toBeArray()->not->toBeEmpty();
});

test('lgas contains Ikeja', function () {
  $lgas = NaijaFaker::lgas();
  expect($lgas)->toContain('Ikeja');
});
