<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class DefaultExtension
 */
class DefaultExtension extends AbstractExtension
{
    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('boolean_readable', [$this, 'formatBoolean'])
        ];
    }

    /**
     * @param bool $input
     * @return string
     */
    public function formatBoolean(bool $input): string
    {
        return $input === true ? 'Ja' : 'Nee';
    }
}