<?php

use Kodegrenade\NaijaFaker\NaijaFaker;

test('email with params', function () {
  $email = NaijaFaker::email('yourname');
  expect($email)->toBeString();
});
