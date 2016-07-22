<?php
// Last commit $Id$
// Version Location $HeadURL$

function getMax2Exp($int, $exp=0) {
  if (pow(2, $exp) <= $int) {
    return getMax2Exp($int, $exp+1);
  }
  else {
    return $exp-1;
  }
}

function getBinaryFromInt($int) {
  # Find the powers and store in an array
  do {
    $exp = getMax2Exp($int);
    $int = $int - pow(2, $exp);
    $binArray[$exp] = 1;
  }	while ($int > 0);
  # Convert the array to a string
  $bin = '';
  for ($i = max(array_keys($binArray)); $i >= 0; $i--) {
    $bin .= isset($binArray[$i]) ? 1 : 0;
  }
  return $bin;
}

//echo (getBinaryFromInt(1000));

function reverseString($string) {
  $reversedString = '';
  for ($i = strlen($string); $i >=0; $i--) {
    $reversedString .= substr($string, $i, 1);
  }
  return $reversedString;
}

function getIntFromBinary($bin) {
  $int = 0;
  $binLen = strlen($bin);
  for ($i = 0; $i <= $binLen; $i++) {
    $digit = substr($bin, $i, 1);
    if ($digit) $int = $int + pow(2, $binLen - $i - 1);
  }
  return $int;
}

//echo getIntFromBinary(10001);

function reverseBinaryInt($int) {
  $bin = getBinaryFromInt($int);
  $reverseBin = reverseString($bin);
  $int = getIntFromBinary($reverseBin);
  return $int;
}

echo reverseBinaryInt(47);