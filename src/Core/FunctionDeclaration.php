<?php

namespace Limpich\Svarog\Core;

use Limpich\Svarog\Core\Base\Node;

class FunctionDeclaration extends Node
{
  public static array $pureFunctionsList = [
    'abs',
    'max',
    'min',
    'rand',
    'round',
    'strtolower',
  ];

  public function getName(): string
  {
    return $this->node->children['name'];
  }

  public function formatNameWithLine(): string
  {
    return "{$this->getName()}:{$this->node->lineno}";
  }

  public function traverseStatements(?callable $callback, ?callable $filter): void
  {
    $this->traverseImpl($this->node->children['stmts'], $callback, $filter);
  }

  /**
   * @return ReturnDeclaration[]
   */
  public function getReturnDeclarations(): array
  {
    $result = [];

    $this->traverseImpl(
      $this->node->children['stmts'],
      function (\ast\Node $return) use (&$result) { $result[] = new ReturnDeclaration($return); },
      fn (\ast\Node $node) => $node->kind === Constants::AST_RETURN,
    );

    return $result;
  }

  /**
   * @return Call[]
   */
  public function getCalls(): array
  {
    $result = [];

    $this->traverseImpl(
      $this->node->children['stmts'],
      function (\ast\Node $return) use (&$result) { $result[] = new Call($return); },
      fn (\ast\Node $node) => $node->kind === Constants::AST_CALL,
    );

    return $result;
  }
}
