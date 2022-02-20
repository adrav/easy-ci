<?php

declare (strict_types=1);
namespace EasyCI20220220\Symplify\Astral\NodeVisitor;

use EasyCI20220220\PhpParser\Node;
use EasyCI20220220\PhpParser\Node\Expr;
use EasyCI20220220\PhpParser\Node\Stmt;
use EasyCI20220220\PhpParser\Node\Stmt\Expression;
use EasyCI20220220\PhpParser\NodeVisitorAbstract;
final class CallableNodeVisitor extends \EasyCI20220220\PhpParser\NodeVisitorAbstract
{
    /**
     * @var callable(Node): (int|Node|null)
     */
    private $callable;
    /**
     * @param callable(Node $node): (int|Node|null) $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }
    /**
     * @return int|Node|null
     */
    public function enterNode(\EasyCI20220220\PhpParser\Node $node)
    {
        $originalNode = $node;
        $callable = $this->callable;
        /** @var int|Node|null $newNode */
        $newNode = $callable($node);
        if ($originalNode instanceof \EasyCI20220220\PhpParser\Node\Stmt && $newNode instanceof \EasyCI20220220\PhpParser\Node\Expr) {
            return new \EasyCI20220220\PhpParser\Node\Stmt\Expression($newNode);
        }
        return $newNode;
    }
}
