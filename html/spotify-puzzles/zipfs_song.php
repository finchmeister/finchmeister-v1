<?php
// Last commit $Id$
// Version Location $HeadURL$

$input = "4 2
30 one
30 two
15 three
25 four";

$input = "15 3
197812 re_hash
78906 5_4
189518 tomorrow_comes_today
39453 new_genious
210492 clint_eastwood
26302 man_research
22544 punk
19727 sound_check
17535 double_bass
18782 rock_the_house
198189 19_2000
13151 latin_simone
12139 starshine
11272 slow_country
10521 m1_a1";

function zipfsSongs($input) { // TODO validate
  $inputArray = preg_split('/\n/', $input);

  $header = array_shift($inputArray);
  preg_match('/(\d+) (\d+)/', $header, $match);
  $noOfSongsToSelect = $match[2];

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
