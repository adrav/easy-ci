<?php

declare (strict_types=1);
namespace EasyCI20220220\Symplify\Astral\NodeNameResolver;

use EasyCI20220220\PhpParser\Node;
use EasyCI20220220\PhpParser\Node\Attribute;
use EasyCI20220220\Symplify\Astral\Contract\NodeNameResolverInterface;
final class AttributeNodeNameResolver implements \EasyCI20220220\Symplify\Astral\Contract\NodeNameResolverInterface
{
    public function match(\EasyCI20220220\PhpParser\Node $node) : bool
    {
        return $node instanceof \EasyCI20220220\PhpParser\Node\Attribute;
    }
    /**
     * @param Attribute $node
     */
    public function resolve(\EasyCI20220220\PhpParser\Node $node) : ?string
    {
        return $node->name->toString();
    }
}
