<?php

namespace Bundle\Nubai;

use Bolt\Extension\SimpleExtension;

/**
 * Site bundle extension loader.
 */
class CustomisationExtension extends SimpleExtension {

    protected function registerTwigFilters() {
        
        return ['shout' => 'shoutFilter'];
    }
    
    public function shoutFilter($text) {
        
        return strtoupper($text) . '!!';
    }

}
