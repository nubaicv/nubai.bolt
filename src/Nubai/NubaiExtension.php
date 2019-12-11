<?php

namespace Bundle\Nubai;

use Bolt\Storage\Entity\Entity;
use Bolt\Storage\Repository;
use Bolt\Extension\SimpleExtension;
use Bolt\Extension\StorageTrait;
use Bolt\Extension\DatabaseSchemaTrait;
use Silex\Application;
use Silex\ControllerCollection;

/**
 * Site bundle extension loader.
 */
class NubaiExtension extends SimpleExtension
{
    
    use StorageTrait;
    use DatabaseSchemaTrait;
    
    protected function registerServices(Application $app) {
        
        $this->extendRepositoryMapping();
        $this->extendDatabaseSchemaServices();
        
    }
    
    protected function registerRepositoryMappings() {
        
        return [
            'customers' => [
                \Bundle\Nubai\Storage\Entity\Customers::class =>
                \Bundle\Nubai\Storage\Repository\CustomersRepository::class
            ],
            'products' => [
                \Bundle\Nubai\Storage\Entity\Products::class =>
                \Bundle\Nubai\Storage\Repository\ProductsRepository::class
            ],
        ];
        
    }
    
    protected function registerExtensionTables() {
        
        return [
            
            'customers' => \Bundle\Nubai\Storage\Schema\Table\Customers::class,
        ];
    }
}
