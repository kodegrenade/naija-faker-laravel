<?php

use Kodegrenade\NaijaFaker\NaijaFaker;

test('title without params returns string', function () {
  $title = NaijaFaker::title();
  expect($title)->toBeString()->not->toBeEmpty();
});

test('title with male gender returns string', function () {
  $title = NaijaFaker::title('male');
  expect($title)->toBeString()->not->toBeEmpty();
});

test('title with female gender returns string', function () {
  $title = NaijaFaker::title('female');
  expect($title)->toBeString()->not->toBeEmpty();
});
