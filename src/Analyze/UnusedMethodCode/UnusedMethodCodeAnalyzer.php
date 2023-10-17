<?php

namespace Limpich\Svarog\Analyze\UnusedMethodCode;

use ast\Node;
use Limpich\Svarog\Core\Assignment;
use Limpich\Svarog\Core\Constants;
use Limpich\Svarog\Core\FunctionDeclaration;

class UnusedMethodCodeAnalyzer
{
  /** @var FunctionDeclaration[] $foundFunctions */
  private array $foundFunctions;

  private function beforeAnalyze(): void
  {
    $this->foundFunctions = [];
  }

  public function analyzeFile(string $filepath): UnusedMethodCodeFileResult
  {
    $this->beforeAnalyze();

    $tree = \ast\parse_file($filepath, 70);
    if (!$tree) {
      return new UnusedMethodCodeFileResult(false);
    }

    $this->findFunctionDeclarations($tree);
    if (!$this->foundFunctions) {
      return (new UnusedMethodCodeFileResult(true))
        ->withFunctions([]);
    }

    $functionResults = [];
    foreach ($this->foundFunctions as $foundFunction) {
      $functionResults[$foundFunction->getName()] = $this->analyzeFunction($foundFunction);
    }

    return (new UnusedMethodCodeFileResult(true))
      ->withFunctions($functionResults);
  }

  private function analyzeFunction(FunctionDeclaration $function): UnusedMethodCodeFunctionResult
  {
    /** @var Assignment[] $assignments */
    $assignments = [];

    $function->traverseStatements(
      function (Node $node) use (&$assignments) {
        $assignments[] = new Assignment($node);
      },
      fn (Node $node) => $node->kind === Constants::AST_ASSIGN,
    );

    $graph = new UnusedMethodCodeVarGraph();
    foreach ($assignments as $assignment) {
      if (!$assignment->leftOperandIsVar()) {
        continue;
      }

      $leftVarName = $assignment->getLeftVarName();
      $graph->addVar($leftVarName);

      $exprVarNames = $assignment->getExprVarsListNames();
      foreach ($exprVarNames as $rightVarName) {
        $graph->addDependency($leftVarName, $rightVarName);
      }
    }

    $returns = $function->getReturnDeclarations();
    foreach ($returns as $return) {
      $exprVarNames = $return->getExprVarsListNames();
      foreach ($exprVarNames as $exprVarName) {
        $graph->addDependency(UnusedMethodCodeVarGraph::SIDE_EFFECT, $exprVarName);
      }
    }

    $calls = $function->getCalls();
    foreach ($calls as $call) {
      if ($call->isPure()) {
        continue;
      }

      $argsVarNames = $call->getArgsVarsListNames();
      var_dump($argsVarNames);
      foreach ($argsVarNames as $argVarName) {
        $graph->addDependency(UnusedMethodCodeVarGraph::SIDE_EFFECT, $argVarName);
      }
    }

    return new UnusedMethodCodeFunctionResult($function, $graph);
  }


  private function findFunctionDeclarations(Node $node): void
  {
    if ($this->isFunctionDeclaration($node)) {
      $this->foundFunctions[] = new FunctionDeclaration($node);
    }

    foreach ($node->children as $child) {
      if ($child instanceof Node) {
        $this->findFunctionDeclarations($child);
      }
    }
  }

  private function isFunctionDeclaration(Node $node): bool
  {
    return in_array($node->kind, [Constants::AST_METHOD, Constants::AST_FUNC_DECL], true);
  }
}
