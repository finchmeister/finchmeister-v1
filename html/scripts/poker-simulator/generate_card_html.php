<?php
// License: www.workbooks.com/mit_license
// Last commit $Id$
// Version Location $HeadURL$

function generateCardHTML_dep($divId, $functionName, $attributes = ""){
  $html = <<<HTML
<select id="{$divId}" class="selectpicker" {$attributes} onchange="{$functionName}()">
    /*<option selected disabled hidden value=''></option>*/
    <option selected value=''></option>
    <optgroup label="Clubs">
        <option value="Ac">Ac</option>
        <option value="Kc">Kc</option>
        <option value="Qc">Qc</option>
        <option value="Jc">Jc</option>
        <option value="10c">10c</option>
        <option value="9c">9c</option>
        <option value="8c">8c</option>
        <option value="7c">7c</option>
        <option value="6c">6c</option>
        <option value="5c">5c</option>
        <option value="4c">4c</option>
        <option value="3c">3c</option>
        <option value="2c">2c</option>
    </optgroup>
    <optgroup label="Diamonds">
        <option value="Ad">Ad</option>
        <option value="Kd">Kd</option>
        <option value="Qd">Qd</option>
        <option value="Jd">Jd</option>
        <option value="10d">10d</option>
        <option value="9d">9d</option>
        <option value="8d">8d</option>
        <option value="7d">7d</option>
        <option value="6d">6d</option>
        <option value="5d">5d</option>
        <option value="4d">4d</option>
        <option value="3d">3d</option>
        <option value="2d">2d</option>
    </optgroup>
    <optgroup label="Hearts">
        <option value="Ah">Ah</option>
        <option value="Kh">Kh</option>
        <option value="Qh">Qh</option>
        <option value="Jh">Jh</option>
        <option value="10h">10h</option>
        <option value="9h">9h</option>
        <option value="8h">8h</option>
        <option value="7h">7h</option>
        <option value="6h">6h</option>
        <option value="5h">5h</option>
        <option value="4h">4h</option>
        <option value="3h">3h</option>
        <option value="2h">2h</option>
    </optgroup>
    <optgroup label="Spades">
        <option value="As">As</option>
        <option value="Ks">Ks</option>
        <option value="Qs">Qs</option>
        <option value="Js">Js</option>
        <option value="10s">10s</option>
        <option value="9s">9s</option>
        <option value="8s">8s</option>
        <option value="7s">7s</option>
        <option value="6s">6s</option>
        <option value="5s">5s</option>
        <option value="4s">4s</option>
        <option value="3s">3s</option>
        <option value="2s">2s</option>
    </optgroup>
</select>
HTML;
  return $html;
}

function generateCardHTML($divId, $functionName, $attributes = ""){
  $html = <<<HTML
<select id="{$divId}" class="selectpicker cardButton" title="Select Card" data-width="115px" {$attributes} onchange="{$functionName}()">

    <optgroup label="Clubs">
        <option value="Ac">Ac</option>
        <option value="Kc">Kc</option>
        <option value="Qc">Qc</option>
        <option value="Jc">Jc</option>
        <option value="10c">10c</option>
        <option value="9c">9c</option>
        <option value="8c">8c</option>
        <option value="7c">7c</option>
        <option value="6c">6c</option>
        <option value="5c">5c</option>
        <option value="4c">4c</option>
        <option value="3c">3c</option>
        <option value="2c">2c</option>
    </optgroup>
    <optgroup label="Diamonds">
        <option value="Ad">Ad</option>
        <option value="Kd">Kd</option>
        <option value="Qd">Qd</option>
        <option value="Jd">Jd</option>
        <option value="10d">10d</option>
        <option value="9d">9d</option>
        <option value="8d">8d</option>
        <option value="7d">7d</option>
        <option value="6d">6d</option>
        <option value="5d">5d</option>
        <option value="4d">4d</option>
        <option value="3d">3d</option>
        <option value="2d">2d</option>
    </optgroup>
    <optgroup label="Hearts">
        <option value="Ah">Ah</option>
        <option value="Kh">Kh</option>
        <option value="Qh">Qh</option>
        <option value="Jh">Jh</option>
        <option value="10h">10h</option>
        <option value="9h">9h</option>
        <option value="8h">8h</option>
        <option value="7h">7h</option>
        <option value="6h">6h</option>
        <option value="5h">5h</option>
        <option value="4h">4h</option>
        <option value="3h">3h</option>
        <option value="2h">2h</option>
    </optgroup>
    <optgroup label="Spades">
        <option value="As">As</option>
        <option value="Ks">Ks</option>
        <option value="Qs">Qs</option>
        <option value="Js">Js</option>
        <option value="10s">10s</option>
        <option value="9s">9s</option>
        <option value="8s">8s</option>
        <option value="7s">7s</option>
        <option value="6s">6s</option>
        <option value="5s">5s</option>
        <option value="4s">4s</option>
        <option value="3s">3s</option>
        <option value="2s">2s</option>
    </optgroup>
</select>
HTML;
  return $html;
}

function generateNoOfPlayersHTML($divId, $functionName) {
  // Note the value that is submitted is the total number of players, the value shown is the number of opponents
  $html = <<<HTML
<select class="selectpicker" id="{$divId}" data-width="234px" onchange="{$functionName}()">
  <option selected value="2">1</option>
  <option value="3">2</option>
  <option value="4">3</option>
  <option value="5">4</option>
  <option value="6">5</option>
  <option value="7">6</option>
  <option value="8">7</option>
  <option value="9">8</option>
</select>
HTML;
  return $html;
}