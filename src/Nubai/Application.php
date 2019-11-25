<?php

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
