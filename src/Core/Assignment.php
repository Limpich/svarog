<?php

namespace Limpich\Svarog\Core;

use Limpich\Svarog\Core\Base\ExpressionNode;

class Assignment extends ExpressionNode
{
  public function leftOperandIsVar(): bool
  {
    return $this->node->children['var']->kind === Constants::AST_VAR;
  }

  public function getLeftVarName(): string
  {
    return $this->node->children['var']->children['name'];
  }
}
