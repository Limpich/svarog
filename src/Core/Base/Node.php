<?php

namespace Limpich\Svarog\Core\Base;

use Limpich\Svarog\Core\Constants;

class Node
{
  public function __construct(
    public readonly \ast\Node $node
  ) { }

  protected function traverseImpl($node, ?callable $callback, ?callable $filter): void
  {
    if (!($node instanceof \ast\Node)) {
      return;
    }

    if (is_null($filter)) {
      $callback($node);
    } else {
      if ($filter($node)) {
        $callback($node);
      }
    }

    switch ($node->kind) {
      case Constants::AST_ARG_LIST:
      case Constants::AST_STMT_LIST:
        foreach ($node->children as $child) {
          $this->traverseImpl($child, $callback, $filter);
        }
        break;

      case Constants::AST_RETURN:
      case Constants::AST_ASSIGN:
        $this->traverseImpl($node->children['expr'], $callback, $filter);
        break;

      case Constants::AST_BINARY_OP:
        $this->traverseImpl($node->children['left'], $callback, $filter);
        $this->traverseImpl($node->children['right'], $callback, $filter);
        break;

      case Constants::AST_CALL:
        foreach ($node->children['args']->children as $child) {
          $this->traverseImpl($child, $callback, $filter);
        }
        break;
    }
  }
}