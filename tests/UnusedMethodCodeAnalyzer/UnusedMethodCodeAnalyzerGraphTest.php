<?php

namespace Limpich\Svarog\Tests\UnusedMethodCodeAnalyzer;

use Limpich\Svarog\Analyze\UnusedMethodCode\UnusedMethodCodeAnalyzer;
use Limpich\Svarog\Analyze\UnusedMethodCode\UnusedMethodCodeVarGraph;
use PHPUnit\Framework\TestCase;

class UnusedMethodCodeAnalyzerGraphTest extends TestCase
{
  private const TEST_0_GRAPH = [
    'a' => [],
    UnusedMethodCodeVarGraph::SIDE_EFFECT => ['a'],
  ];
  private const REDUNDANT_VARS_0 = [];

  private const TEST_1_GRAPH = [
    'a' => [],
    'b' => ['a'],
    'c' => ['a', 'b'],
    'd' => [],
    UnusedMethodCodeVarGraph::SIDE_EFFECT => ['a', 'd'],
  ];
  private const REDUNDANT_VARS_1 = ['b', 'c'];

  private const TEST_2_GRAPH = [
    'a' => [],
    'b' => ['a'],
    'c' => ['b'],
    's' => [],
    UnusedMethodCodeVarGraph::SIDE_EFFECT => ['a', 'b', 'c'],
  ];
  private const REDUNDANT_VARS_2 = ['s'];

  public static function dataProvider1(): array
  {
    return [
      [
        'functionName' => 'test0',
        'expect' => [
          'graph' => self::TEST_0_GRAPH,
          'vars' => self::REDUNDANT_VARS_0,
        ],
      ],
      [
        'functionName' => 'test1',
        'expect' => [
          'graph' => self::TEST_1_GRAPH,
          'vars' => self::REDUNDANT_VARS_1,
        ],
      ],
      [
        'functionName' => 'test2',
        'expect' => [
          'graph' => self::TEST_2_GRAPH,
          'vars' => self::REDUNDANT_VARS_2,
        ],
      ],
    ];
  }

  /**
   * @dataProvider dataProvider1
   * @param string $functionName
   * @param array $expect
   * @return void
   */
  public function test1(string $functionName, array $expect): void
  {
    $analyzer = new UnusedMethodCodeAnalyzer();

    $result = $analyzer->analyzeFile(__DIR__ . '/../data/1.php');

    $this->assertEquals($functionName, $result->getFunctions()[$functionName]
      ->getFunction()->getName());

    $this->assertEqualsCanonicalizing($expect['graph'], $result->getFunctions()[$functionName]
      ->getGraph()->getDependencies());

    $this->assertEqualsCanonicalizing($expect['vars'], $result->getFunctions()[$functionName]
      ->getGraph()->getUnusedVars());
  }
}
