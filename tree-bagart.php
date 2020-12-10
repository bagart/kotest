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
    public FlatTree $tree;
    private ?array $reversIndex = null;// лучше носить вместе с FlatTree но предположим делаем сервис сбоку

    public function __construct(FlatTree $tree)
    {
        $this->tree = $tree;
    }

    public function reset(): void
    {
        $this->reversIndex = null;
    }

    public function init(): void
    {
        if ($this->reversIndex !== null) {
            return;
        }

        foreach ($this->tree->nodes as $node) {
            $this->reversIndex[$node->parent][] = $node;
        }
    }

    private function getFromRevert(int $parent): Generator
    {
        foreach ($this->reversIndex[$parent] ?? [] as $node) {
            yield $node;
            foreach ($this->getFromRevert($node->id) as $sub) {
                yield $sub;
            }
        }
    }

    public function getTree(int $parent): ?Generator
    {
        if (!isset($this->tree->nodes[$parent])) {
            return null;
        }
        $this->init();
        return $this->getFromRevert($parent);
    }
}

$mem_init = memory_get_peak_usage();
$tree = unserialize(file_get_contents('tree.dat'));

$builder = new TreeBuilder($tree);
$mem_before_prepare = memory_get_usage();
$builder->init();//вызывать не обязательно
echo '+mem after prepare revert index: ' . round((memory_get_usage() - $mem_before_prepare) / 1024 / 1024, 2) . "mb (without remove of revert index)\n\n";

$mem = memory_get_peak_usage();
$time = microtime(true);

$result = $builder->getTree(1);
$count = 0;
foreach ($result as $node) {
    //echo $node->value."\n";
    ++$count;
}

echo "result: $count\n";
echo 'time: ' . round((microtime(true) - $time), 2) . "s\n";
echo '+mem peak after data load: ' . round(($mem - $mem_init) / 1024 / 1024, 2) . "mb\n";
echo '+mem peak after getTree: ' . round((memory_get_peak_usage() - $mem) / 1024 / 1024, 2) . "mb\n\n";

echo 'mem final with revert index: ' . round(memory_get_usage() / 1024 / 1024, 2) . "mb\n\n";

$builder->reset();
echo 'mem final without revert index: ' . round(memory_get_usage() / 1024 / 1024, 2) . "mb\n\n";
