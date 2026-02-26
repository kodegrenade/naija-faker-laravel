<?php

/*
 * This file is part of the Laravel NaijaFaker package.
 *
 * (c) Temitope Ayotunde <brhamix@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

return [
  'bands' => [
    'entry' => ['min' => 50000, 'max' => 150000],
    'mid' => ['min' => 150000, 'max' => 500000],
    'senior' => ['min' => 500000, 'max' => 1500000],
    'executive' => ['min' => 1500000, 'max' => 5000000],
  ],
  'levels' => ['entry', 'mid', 'senior', 'executive'],
];
