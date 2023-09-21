<?php

/*
 * This file is part of the Laravel NaijaFaker package.
 *
 * (c) Temitope Ayotunde <brhamix@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace Kodegrenade\NaijaFaker\Test;

use PHPUnit\Framework\TestCase;
use Kodegrenade\NaijaFaker\NaijaFaker;

class NaijaFakerTest extends TestCase
{
  public function testNameWithoutParams()
  {
    $name = NaijaFaker::name();
    $this->assertIsString($name);
  }

  public function testNameWithParams()
  {
    $name = NaijaFaker::name('yoruba', 'male');
    $this->assertIsString($name);
  }

  public function testPersonWithoutParams()
  {
    $person = NaijaFaker::person();
    $this->assertIsObject($person);
  }

  public function testPersonWithParams()
  {
    $person = NaijaFaker::person('hausa', 'female');
    $this->assertIsObject($person);
  }

  public function testPeopleWithoutParams()
  {
    $people = NaijaFaker::people();
    $this->assertIsArray($people);
  }

  public function testPeopleWithParams()
  {
    $people = NaijaFaker::people(3, 'igbo', 'female');
    $this->assertIsArray($people);
  }

  public function testTitleWithoutParams()
  {
    $title = NaijaFaker::title();
    $this->assertIsString($title);
  }

  public function testTitleWithParams()
  {
    $title = NaijaFaker::title('male');
    $this->assertIsString($title);
  }

  public function testEmailWithParams()
  {
    $email = NaijaFaker::email('yourname');
    $this->assertIsString($email);
  }

  public function testAddress()
  {
    $address = NaijaFaker::address();
    $this->assertIsString($address);
  }

  public function testPhoneNumberWithoutParams()
  {
    $phoneNumber = NaijaFaker::phoneNumber();
    $this->assertIsString($phoneNumber);
  }

  public function testPhoneNumberWithParams()
  {
    $phoneNumber = NaijaFaker::phoneNumber('mtn');
    $this->assertIsString($phoneNumber);
  }

  public function testAllStatesAreReturned()
  {
    $states = NaijaFaker::states();
    $this->assertIsArray($states);
  }

  public function testAllLgasAreReturned()
  {
    $lgas = NaijaFaker::lgas();
    $this->assertIsArray($lgas);
  }
}
