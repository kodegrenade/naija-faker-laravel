<?php

use Kodegrenade\NaijaFaker\NaijaFaker;

test('title without params', function () {
  $title = NaijaFaker::title();
  expect($title)->toBeString();
});

test('title with params', function () {
  $title = NaijaFaker::title('male');
  expect($title)->toBeString();
});
