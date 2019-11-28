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
    
    public function emailExists($email) {
        
        if (!$this->findBy(['email' => $email])) {
            return false;
        }
        
        return true;
    }
    
    public function verifyCredentials($email, $password) {
        
        $customer = $this->findBy(['email' => $email, 'password' => $password]);
        
        if (!$customer) {
            return false;
        }
        
        return $customer;
    }
    
    
    // -----------------------------------------------------------------
    public function createQueryBuilder($alias = null) {
        
        if(empty($alias)) {
            $alias = $this->getAlias();
        }
        
        return parent::createQueryBuilder($alias);
    }
}
