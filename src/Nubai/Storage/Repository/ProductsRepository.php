<?php

namespace Bundle\Nubai\Storage\Repository;

use Bundle\Nubai\Storage\Entity\Products;
use Bolt\Storage\Repository\ContentRepository;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Description of ProductsRepository
 *
 * @author ricardo
 */
class ProductsRepository extends ContentRepository {
    
    /**
     * 
     * @param int $id
     * @param int $value
     * @return int
     */
    public function increaseStock($id, $value) {
        
        $product = $this->find($id);
        $product->setStock($product->stock + $value);
        $this->save($product);
        
        return $product->stock;
    }
    
    /**
     * 
     * @param int $id
     * @param int $value
     * @return int
     */
    public function decreaseStock($id, $value) {
        
        $product = $this->find($id);
        $product->setStock($product->stock - $value);
        $this->save($product);
        
        return $product->stock;
    }
    
    
    // --------------------------------------------------------------------
    public function createQueryBuilder($alias = null) {
        
        if(empty($alias)) {
            $alias = $this->getAlias();
        }
        
        return parent::createQueryBuilder($alias);
    }
}
