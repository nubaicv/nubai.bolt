<?php

namespace Bundle\Nubai\Storage\Repository;

use Bundle\Nubai\Storage\Entity\Sessions;
use Bolt\Storage\Repository;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Description of SessionsRepository
 *
 * @author ricardo
 */
class SessionsRepository extends Repository {
    
    public function doWrite($sid, $sdata) {
        
        $entity = $this->create(['sid' => '76743459459', 'sdata' => 'sdatasdatasdata']);
        $this->save($entity);
    }
}
