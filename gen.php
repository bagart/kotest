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
$tree = new FlatTree();
for ($i = 0; $i < $argv[1]??100000; ++$i) {
    $node = new Node();
    $node->id = $i;
    $node->parent = $i === 0 ? null : rand(0, $argv[2]??100);
    $node->value = "value with parent {$node->parent} for #$i";
    $tree->nodes[] = $node;
}
file_put_contents('tree.dat', serialize($tree));

echo 'size of data: ' . round(filesize('tree.dat') / 1024 / 1024, 2) . "mb\n";