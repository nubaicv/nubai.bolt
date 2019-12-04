<?php

namespace Bundle\Nubai\Storage\Schema\Table;

use Bolt\Storage\Database\Schema\Table\BaseTable;

/**
 * Description of Sessions
 *
 * @author ricardo
 */
class Sessions extends BaseTable {
    
    protected function addColumns() {
        
        $this->table->addColumn('sid', 'string', ['length' => 100]);
        $this->table->addColumn('sdata', 'string', ['notnull' => false]);
        $this->table->addColumn('expires', 'datetime', ['notnull' => false]);
        $this->table->addColumn('ip', 'string', ['notnull' => false, 'length' => 50]);
    }
    
    protected function addIndexes() {
    }
    
    protected function setPrimaryKey() {
        
        $this->table->setPrimaryKey(['sid']);
    }
}
