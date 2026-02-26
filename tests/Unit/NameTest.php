<?php

use Kodegrenade\NaijaFaker\NaijaFaker;

test('name without params returns string with space', function () {
  $name = NaijaFaker::name();
  expect($name)->toBeString()->toContain(' ');
});

test('name with language and gender returns string', function () {
  $name = NaijaFaker::name('yoruba', 'male');
  expect($name)->toBeString()->toContain(' ');
});

test('name with each language works', function () {
  foreach (['yoruba', 'igbo', 'hausa'] as $lang) {
    $name = NaijaFaker::name($lang);
    expect($name)->toBeString()->toContain(' ');
  }
});

test('name with each gender works', function () {
  foreach (['male', 'female'] as $gender) {
    $name = NaijaFaker::name(null, $gender);
    expect($name)->toBeString()->toContain(' ');
  }
});
