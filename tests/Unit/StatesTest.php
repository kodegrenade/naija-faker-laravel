<?php

use Kodegrenade\NaijaFaker\NaijaFaker;

test('states returns non-empty array', function () {
  $states = NaijaFaker::states();
  expect($states)->toBeArray()->not->toBeEmpty();
});

test('states contains Lagos', function () {
  $states = NaijaFaker::states();
  expect($states)->toContain('Lagos');
});

test('states contains FCT', function () {
  $states = NaijaFaker::states();
  expect($states)->toContain('FCT (Abuja)');
});
