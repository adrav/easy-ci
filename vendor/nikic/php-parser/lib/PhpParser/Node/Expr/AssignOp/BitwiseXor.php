<?php

declare (strict_types=1);
namespace EasyCI20220610\PhpParser\Node\Expr\AssignOp;

use EasyCI20220610\PhpParser\Node\Expr\AssignOp;
class BitwiseXor extends AssignOp
{
    public function getType() : string
    {
        return 'Expr_AssignOp_BitwiseXor';
    }
}
