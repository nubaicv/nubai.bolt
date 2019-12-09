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
    
    public function registerCustomer($data) {
        
        $entity = $this->create($data);
        $entity->setDatecreated(new \DateTime);
        $entity->setDatechanged(new \DateTime);
        $entity->setEmail_verification_code(bin2hex(random_bytes(20)));
        $entity->setActivated(0);
        
        try {
            
            return $this->save($entity);
        } catch (Exception $ex) {
            
            return $ex;
        }
    }
    
    
    // -----------------------------------------------------------------
    public function createQueryBuilder($alias = null) {
        
        if(empty($alias)) {
            $alias = $this->getAlias();
        }
        
        return parent::createQueryBuilder($alias);
    }
}
