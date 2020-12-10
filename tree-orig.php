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
    public function getTree(FlatTree $normalizedTree, int $parent): ?Generator
    {
        foreach ($normalizedTree->nodes as $key => $normalizedTreeNode) {
            if ($normalizedTreeNode->parent !== $parent) {
                continue;
            }

            yield $normalizedTreeNode;

            foreach ($this->getTree($normalizedTree, $normalizedTreeNode->id) as $node) {
                yield $node;
            }
        }
    }
}

$mem_init = memory_get_peak_usage();
$tree = unserialize(file_get_contents('tree.dat'));

$builder = new TreeBuilder();
$mem = memory_get_peak_usage();
$time = microtime(true);

$result = $builder->getTree($tree, 1);

$count = 0;
foreach ($result as $node) {
    //echo $node->value."\n";
    ++$count;
}

echo 'time: ' . round((microtime(true) - $time), 2) . "s\n";
echo '+mem peak after data load: ' . round(($mem - $mem_init) / 1024 / 1024, 2) . "mb\n";
echo '+mem peak after getTree: ' . round((memory_get_peak_usage() - $mem) / 1024 / 1024, 2) . "mb\n\n";
echo '+mem final: ' . round(memory_get_usage() / 1024 / 1024, 2) . "mb\n\n";


echo "result: $count\n";
