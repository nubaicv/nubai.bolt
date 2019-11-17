<?php

namespace Bundle\Nubai;

use Bolt\Extension\SimpleExtension;

/**
 * Site bundle extension loader.
 */
class CustomisationExtension extends SimpleExtension {

    protected function registerTwigFilters() {
        
        return ['shout' => 'shoutFiltera'];
    }
    
    public function shoutFiltera($text) {
        
        return strtoupper($text) . '!!';
    }

}
