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

test('person without params', function () {
    $person = NaijaFaker::person();
    expect($person)->toBeObject();
});

test('person with params', function () {
    $person = NaijaFaker::person('hausa', 'female');
    expect($person)->toBeObject();
});

test('people without params', function () {
    $people = NaijaFaker::people();
    expect($people)->toBeArray();
});

test('people with params', function () {
    $people = NaijaFaker::people(3, 'igbo', 'female');
    expect($people)->toBeArray();
});

test('title without params', function () {
    $title = NaijaFaker::title();
    expect($title)->toBeString();
});

test('title with params', function () {
    $title = NaijaFaker::title('male');
    expect($title)->toBeString();
});

test('email with params', function () {
    $email = NaijaFaker::email('yourname');
    expect($email)->toBeString();
});

test('address', function () {
    $address = NaijaFaker::address();
    expect($address)->toBeString();
});

test('phone number without params', function () {
    $phoneNumber = NaijaFaker::phoneNumber();
    expect($phoneNumber)->toBeString();
});

test('phone number with params', function () {
    $phoneNumber = NaijaFaker::phoneNumber('mtn');
    expect($phoneNumber)->toBeString();
});

test('all states are returned', function () {
    $states = NaijaFaker::states();
    expect($states)->toBeArray();
});

test('all lgas are returned', function () {
    $lgas = NaijaFaker::lgas();
    expect($lgas)->toBeArray();
});
