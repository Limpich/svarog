<?php

namespace Limpich\Svarog\Core\Base;

use Limpich\Svarog\Core\Constants;

class ExpressionNode extends Node
{
  public function getExprVarsListNames(): array
  {
    $result = [];

    $this->traverseImpl(
      $this->node->children['expr'],
      function (\ast\Node $node) use (&$result) { $result[] = $node->children['name']; },
      fn (\ast\Node $node) => $node->kind === Constants::AST_VAR,
    );

    return array_unique($result);
  }
}
