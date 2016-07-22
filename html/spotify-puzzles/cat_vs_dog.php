<?php
// Last commit $Id$
// Version Location $HeadURL$

function catVsDog($input) { // TODO validate
  $inputArray = preg_split('/\n/', $input);

  $noOfTestCases = array_shift($inputArray);

  if ($noOfTestCases > 0 && $noOfTestCases <= 100) {
    $j = 0;
    for ($i = 0; $i < $noOfTestCases; $i++) {
      preg_match('/(\d+) (\d+) (\d+)/', $inputArray[$i+$j], $match);
      list($c, $d, $v) = [$match[1], $match[2], $match[3],];
      # Validate
      if ($v < 0 || $v > 500) $errors[] = 'v must be between 0 and 500';





    }












  }
  else {
    //Exception too many test cases
  }

  $noOfTestCases = $match[1];




  foreach ($inputArray as $index => $row) {
    preg_match('/([0-9]+) ([0-9a-z_]+)/', $row, $match);
    $zipfs = 1/($index+1);
    $songs[$index] = [
      'plays' => $match[1],
      'name' => $match[2],
      'quality' => $match[1]/$zipfs,
    ];
    $quality[$index] = $songs[$index]['quality'];
  }
  array_multisort($quality, SORT_DESC, array_keys($songs), SORT_ASC, $songs);

  for ($i = 0; $i < $noOfSongsToSelect; $i++) {
    $orderedBestSongs[] = $songs[$i]['name'];
  }

  return implode("\n", $orderedBestSongs);
}
