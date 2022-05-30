<?php

declare (strict_types=1);
namespace EasyCI20220530\PHPStan\PhpDocParser\Ast\ConstExpr;

use EasyCI20220530\PHPStan\PhpDocParser\Ast\NodeAttributes;
class ConstExprNullNode implements \EasyCI20220530\PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprNode
{
    use NodeAttributes;
    public function __toString() : string
    {
        return 'null';
    }
}
