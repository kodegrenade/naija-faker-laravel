<?php

use Kodegrenade\NaijaFaker\NaijaFaker;

test('people without params', function () {
  $people = NaijaFaker::people();
  expect($people)->toBeArray();
});

test('people with params', function () {
  $people = NaijaFaker::people(3, 'igbo', 'female');
  expect($people)->toBeArray();
});
