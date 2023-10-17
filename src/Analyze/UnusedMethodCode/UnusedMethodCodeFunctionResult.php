<?php

namespace Limpich\Svarog\Analyze\UnusedMethodCode;

use Limpich\Svarog\Core\FunctionDeclaration;

class UnusedMethodCodeFunctionResult
{
  public function __construct(
    private FunctionDeclaration $function,
    private UnusedMethodCodeVarGraph $graph,
  ) { }

  public function getFunction(): FunctionDeclaration
  {
    return $this->function;
  }

  public function getGraph(): UnusedMethodCodeVarGraph
  {
    return $this->graph;
  }
}
