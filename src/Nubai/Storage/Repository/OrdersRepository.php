<?php

namespace Bundle\Nubai\Storage\Repository;

use Bundle\Nubai\Storage\Entity\Orders;
use Bolt\Storage\Repository;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Description of OrdersRepository
 *
 * @author ricardo
 */
class OrdersRepository extends Repository {
    
    
    
    // -----------------------------------------------------------------
    public function createQueryBuilder($alias = null) {
        
        if(empty($alias)) {
            $alias = $this->getAlias();
        }
        
        return parent::createQueryBuilder($alias);
    }
}