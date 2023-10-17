<?php

namespace Limpich\Svarog\Analyze\UnusedMethodCode;

class UnusedMethodCodeFileResult
{
  /**
   * @var UnusedMethodCodeFunctionResult[]
   */
  private array $functions = [];

  public function __construct(
    private readonly bool $success,
  ) { }

  /**
   * @return bool
   */
  public function isSuccess(): bool
  {
    return $this->success;
  }

  /**
   * @return UnusedMethodCodeFunctionResult[]
   */
  public function getFunctions(): array
  {
    return $this->functions;
  }

  /**
   * @param UnusedMethodCodeFunctionResult[] $fountFunctions
   * @return self
   */
  public function withFunctions(array $fountFunctions): self
  {
    $this->functions = $fountFunctions;

    return $this;
  }
}
