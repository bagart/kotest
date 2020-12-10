<?php

class Node
{
    public int $id;
    public ?int $parent;
    public string $value;
}

class FlatTree
{
    /** @var Node[] */
    public array $nodes = [];
}

class TreeBuilder
{
    private ?array $revert = null;// лучше носить вместе с FlatTree но предположим делаем сервис сбоку
    public FlatTree $tree;

    public function __construct(FlatTree $tree)
    {
        $this->tree = $tree;
    }

    public function reset(): void
    {
        $this->revert = null;
    }

    private function init(): void
    {
        if ($this->revert !== null) {
            return;
        }

        foreach ($this->tree->nodes as $node) {
            $this->revert[$node->parent][] = $node;
        }
    }

    private function getFromRevert(int $parent): Generator
    {
        foreach ($this->revert[$parent] ?? [] as $node) {
            yield $node;
            foreach ($this->getFromRevert($node->id) as $sub) {
                yield $sub;
            }
        }
    }

    public function getTree(int $parent, bool $memoize = false): ?Generator
    {
        if (!isset($this->tree->nodes[$parent])) {
            return null;
        }
        $this->init();
        return $this->getFromRevert($parent);
    }

}

$tree = unserialize(file_get_contents('tree.dat'));
$mem = memory_get_peak_usage();
$time = microtime(true);

$result = (new TreeBuilder($tree))->getTree(1);
$count = 0;
foreach ($result as $node) {
    //echo $node->value."\n";
    ++$count;
}
echo "result: $count\n";
echo 'time: ' . round((microtime(true) - $time) / 1000, 2) . "s\n";
echo 'mem diff: ' . round((memory_get_peak_usage() - $mem) / 1024, 2) . "kb\n";