<?php

namespace Bundle\Nubai\Storage\Repository;

use Bundle\Nubai\Storage\Entity\Customers;
use Bolt\Storage\Repository;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Description of CustomersRepository
 *
 * @author ricardo
 */
class CustomersRepository extends Repository {
    
    
    // -----------------------------------------------------------------
    public function createQueryBuilder($alias = null) {
        
        if(empty($alias)) {
            $alias = $this->getAlias();
        }
        
        return parent::createQueryBuilder($alias);
    }
}
