<?php

namespace Kodegrenade\NaijaFaker\Library;

class Library
{
  protected static $libraries = [
    'address',
    'email',
    'location',
    'names',
    'numbers',
    'title'
  ];

  static function getLibraryData($provider)
  {
    foreach (self::$libraries as $library) {
      $libraryPath = __DIR__ . '/' . $library . '/' . $provider . '.php';
      if (file_exists($libraryPath)) {
        return include $libraryPath;
      }
    }

    return false;
  }
}