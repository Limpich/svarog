<?php

namespace Limpich\Svarog\Core;

use Limpich\Svarog\Core\Base\ArgsNode;

class Call extends ArgsNode
{
  public function getName(): string
  {
    return $this->node->children['expr']->children['name'];
  }

  public function isPure(): bool
  {
    return in_array($this->getName(), FunctionDeclaration::$pureFunctionsList, true);
  }
}
