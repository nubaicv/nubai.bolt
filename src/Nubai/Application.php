<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Bundle\Nubai;

use Bolt\Application as Nubai;

/**
 * Description of Application
 *
 * @author ricardo
 */

class Application extends Nubai {
    
    public function initProviders() {
        
        parent::initProviders();
        $this['controller.frontend'] = $this->share(
                function () {
                    return new FrontendController();
                }
        );
    }
}
