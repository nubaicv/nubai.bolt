<?php

namespace Bundle\Nubai;

use Bolt\Storage\Entity\Entity;
use Bolt\Storage\Repository;
use Bolt\Extension\SimpleExtension;
use Bolt\Extension\StorageTrait;
use Silex\Application;

/**
 * Site bundle extension loader.
 */
class CustomisationExtension extends SimpleExtension
{
    
    use StorageTrait;
    
    protected function registerServices(Application $app) {
        
        $this->extendRepositoryMapping();
        
    }
    
    protected function registerRepositoryMappings() {
        
        return [
            'customers' => [
                \Bundle\Nubai\Storage\Entity\Customers::class =>
                \Bundle\Nubai\Storage\Repository\CustomersRepository::class
            ],
        ];
        
    }
    
}
