<?php

namespace Bundle\Nubai\Storage\Repository;

use Bundle\Nubai\Storage\Entity\OrderItems;
use Bolt\Storage\Repository;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Description of OrderItemsRepository
 *
 * @author ricardo
 */
class OrderItemsRepository extends Repository {
    
    
    
    // -----------------------------------------------------------------
    public function createQueryBuilder($alias = null) {
        
        if(empty($alias)) {
            $alias = $this->getAlias();
        }
        
        return parent::createQueryBuilder($alias);
    }
}
