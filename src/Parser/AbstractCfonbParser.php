<?php

/*
 * This file is part of the CFONB Parser package.
 *
 * (c) Guillaume Sainthillier <hello@silarhi.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Silarhi\Cfonb\Parser;

use Silarhi\Cfonb\Contracts\ParserInterface;
use Silarhi\Cfonb\Exceptions\ParseException;

abstract class AbstractCfonbParser implements ParserInterface
{
    public const BLANK = '( {%d})';

    public const NUMERIC = '(\d{%d})';
    public const NUMERIC_BLANK = '([0-9 ]{%d})';

    public const ALPHA = '(\w{%d})';
    public const ALPHA_BLANK = '([a-zA-Z ]{%d})';

    public const ALPHANUMERIC = '([a-zA-Z0-9]{%d})';
    public const ALPHANUMERIC_BLANK = '([a-zA-Z0-9 ]{%d})';

    public const AMOUNT = '(\d{13}[{}A-R]{1})';

    public const ALL = '(.{%d})';

    protected function parseLine($content, array $parts)
    {
        $regexParts = [];
        foreach ($parts as $part) {
            $regexParts[] = \is_array($part) ? sprintf($part[0], $part[1]) : $part;
        }

        $regex = sprintf('/^%s$/', implode('', $regexParts));

        if (!preg_match($regex, $content, $matches)) {
            throw new ParseException(sprintf('Regex does not match the line'));
        }

        $values = [];
        foreach (array_keys($parts) as $i => $key) {
            $value = trim($matches[$i + 1]);
            $values[$key] = \strlen($value) > 0 ? $value : null;
        }

        return $values;
    }

    protected function parseAmount($content, $nbDecimals)
    {
        $creditMapping = [
            'A' => '1',
            'B' => '2',
            'C' => '3',
            'D' => '4',
            'E' => '5',
            'F' => '6',
            'G' => '7',
            'H' => '8',
            'I' => '9',
            '{' => '0',
        ];

        $debitMapping = [
            'J' => '1',
            'K' => '2',
            'L' => '3',
            'M' => '4',
            'N' => '5',
            'O' => '6',
            'P' => '7',
            'Q' => '8',
            'R' => '9',
            '}' => '0',
        ];

        $content = substr($content, 0, \strlen($content) - $nbDecimals) . '.' . substr($content, -1 * $nbDecimals);
        $lastChar = substr($content, -1);
        if (isset($creditMapping[$lastChar])) {
            return (float) (str_replace($lastChar, $creditMapping[$lastChar], $content));
        }

        if (isset($debitMapping[$lastChar])) {
            return -1.0 * (float) (str_replace($lastChar, $debitMapping[$lastChar], $content));
        }

        throw new ParseException(sprintf('Unable to parse amount "%s"', $content));
    }

    /**
     * @param $date
     *
     * @return \DateTime
     */
    protected function parseDate($date)
    {
        $datetime = \DateTime::createFromFormat('dmy', $date);
        if (false === $datetime) {
            throw new ParseException(sprintf('Unable to parse date "%s"', $date));
        }

        return $datetime->setTime(0, 0);
    }

    abstract protected function getSupportedCode();

    protected function getNumericalValue($content, $position, $length)
    {
        return (int) ltrim($this->getValue($content, $position, $length), '0');
    }

    protected function getAlphabeticalValue($content, $position, $length)
    {
        return $this->getAlphanumericalValue($content, $position, $length);
    }

    protected function getAlphanumericalValue($content, $position, $length)
    {
        return rtrim($this->getValue($content, $position, $length));
    }

    private function getValue($content, $position, $length)
    {
        return substr($content, $position - 1, $length);
    }
}