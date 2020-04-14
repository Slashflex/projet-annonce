<?php

namespace App\Twig;

use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('tronquer', [$this, 'tronq']),
        ];
    }

    public function tronq($string, $limitCharacter)
    {
        return substr($string, 0, $limitCharacter);
    }

    // Possibilité de créer une fonction pour faire la même action
    // public function getFunctions()
    // {
    //     return [
    //         new TwigFunction('tronquer', function($string, $limit) {
    //             return substr($string, 0, $limit) . '...';
    //         }),
    //     ];
    // }
}