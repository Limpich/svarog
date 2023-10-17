<?php

namespace Limpich\Svarog\Analyze\UnusedMethodCode;

class UnusedMethodCodeVarGraph
{
  public const SIDE_EFFECT = 'SIDE_EFFECT';

  private array $varDependencies = [];

  public function addVar(string $var): void
  {
    if (!isset($this->varDependencies[$var])) {
      $this->varDependencies[$var] = [];
    }
  }

  public function addDependency(string $var, string $from): void
  {
    $this->addVar($var);
    $this->varDependencies[$var][] = $from;
  }

  public function getDependencies(): array
  {
    return $this->varDependencies;
  }

  public function getUnusedVars(): array
  {
    $important = [];
    foreach ($this->getDependencies() as $var => $dependencies) {
      $important[$var] = false;
      foreach ($dependencies as $dependency) {
        $important[$dependency] = false;
      }
    }

    $this->getUnusedVarsImpl($important, self::SIDE_EFFECT);

    $result = [];
    foreach ($important as $var => $value) {
      if (!$value && $var !== self::SIDE_EFFECT) {
        $result[] = $var;
      }
    }

    return array_unique($result);
  }

  private function getUnusedVarsImpl(array &$important, string $currentVar): void
  {
    if ($important[$currentVar]) {
      return;
    }

    foreach ($this->getDependencies()[$currentVar] as $dependency) {
      $important[$dependency] = true;
      $this->getUnusedVarsImpl($important, $dependency);
    }
  }
}
