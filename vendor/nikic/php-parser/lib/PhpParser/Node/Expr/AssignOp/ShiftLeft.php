<?php

declare (strict_types=1);
namespace EasyCI20220525\PhpParser\Node\Expr\AssignOp;

use EasyCI20220525\PhpParser\Node\Expr\AssignOp;
class ShiftLeft extends \EasyCI20220525\PhpParser\Node\Expr\AssignOp
{
    public function getType() : string
    {
        return 'Expr_AssignOp_ShiftLeft';
    }
}
