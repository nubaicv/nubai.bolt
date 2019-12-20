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
        
        $entity = $this->findOneBy(['email' => $email,]);
        
        if (!$entity) {
            return false;
        }
        
        if (!password_verify($password, $entity->getPassword())) {
            return false;
        }
        
        return $entity;
    }
    
    public function registerCustomer($data) {
        
        $entity = $this->create($data);
        $entity->setDatecreated(new \DateTime);
        $entity->setDatechanged(new \DateTime);
        $emailverificationcode = bin2hex(random_bytes(20));
        $entity->setEmail_verification_code($emailverificationcode);
        $entity->setActivated(0);
        
        try {
            
            if($this->save($entity)) {
                return $emailverificationcode;
            }
        } catch (Exception $ex) {
            
            return $ex;
        }
    }
    
    public function createPasswordRecoveryCode($email) {
        
        $entity = $this->findOneBy(['email' => $email]);
        $passwordrecoverycode = bin2hex(random_bytes(20));
        $entity->setDatechanged(new \DateTime);
        $entity->setPassword_recovery_code($passwordrecoverycode);
        
        try {
            
            if($this->save($entity)) {
                return $passwordrecoverycode;
            } else {
                return false;
            }
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
