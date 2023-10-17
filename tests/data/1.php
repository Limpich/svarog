<?php

function test0(): int
{
  $a = 42;

  return $a;
}

function test1(): int
{
  $a = 42;
  $b = $a * 42;
  $c = round(rand($a, 2 * $a), 10) * $b;

  $d = 1;

  return $a * $d;
}

function test2(): int
{
  $a = 42;
  $b = $a + 42 + $a - 42 + $a * $a / ($a / 42);
  $c = round(round(round(round($b))));

  $s = '123';
  strtolower($s);

  return $a * $b * $c;
}
