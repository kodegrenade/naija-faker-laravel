<?php

/*
 * This file is part of the Laravel NaijaFaker package.
 *
 * (c) Temitope Ayotunde <brhamix@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace Kodegrenade\NaijaFaker\Exceptions;

class NaijaFakerException extends \Exception
{
  protected string $errorCode;

  public function __construct(string $message, string $code)
  {
    parent::__construct($message);
    $this->errorCode = $code;
  }

  public function getErrorCode(): string
  {
    return $this->errorCode;
  }
}
