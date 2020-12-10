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

            foreach($this->getTree($normalizedTree, $normalizedTreeNode->id) as $node){
                yield $node;
            }
        }
    }
}

$tree = unserialize(file_get_contents('tree.dat'));
$mem = memory_get_peak_usage();
$time = microtime(true);

$result = (new TreeBuilder())->getTree($tree, 1);
$count = 0;
foreach ($result as $node) {
    //echo $node->value."\n";
    ++$count;
}

echo "result: $count\n";
echo 'time: ' . round((microtime(true) - $time), 2) . "s\n";
echo 'mem diff: ' . round((memory_get_peak_usage() - $mem) / 1024, 2) . "kb\n\n";