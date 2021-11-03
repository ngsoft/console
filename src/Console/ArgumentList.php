<?php

declare(strict_types=1);

namespace NGSOFT\Console;

use NGSOFT\Console\Utils\ListItem;

class ArgumentList extends ListItem {

    /** {@inheritdoc} */
    protected function itemInstanceOf(): string {
        return Argument::class;
    }

}
