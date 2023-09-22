<?php

use Kodegrenade\NaijaFaker\NaijaFaker;

test('name without params', function () {
  $name = NaijaFaker::name();
  expect($name)->toBeString();
});

test('name with params', function () {
  $name = NaijaFaker::name('yoruba', 'male');
  expect($name)->toBeString();
});
