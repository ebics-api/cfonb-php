<?php

/*
 * This file is part of the CFONB Parser package.
 *
 * (c) Guillaume Sainthillier <hello@silarhi.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Silarhi\Cfonb;

use Silarhi\Cfonb\Contracts\ParserInterface;

abstract class AbstractReader
{
    /** @var ParserInterface[] */
    protected $parsers = [];

    abstract public function parse($content);
}