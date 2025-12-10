<?php

namespace App\Enum;

enum InventoryStatusEnum: string
{
    case InStock = "INSTOCK";
    case LowStock = "LOWSTOCK";
    case OutOfStock = "OUTOFSTOCK";
}