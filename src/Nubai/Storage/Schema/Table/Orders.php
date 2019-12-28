<?php

namespace Bundle\Nubai\Storage\Schema\Table;

use Bolt\Storage\Database\Schema\Table\BaseTable;

/**
 * Description of Orders
 *
 * @author ricardo
 */
class Orders extends BaseTable {
    
    protected function addColumns() {
        
        $this->table->addColumn('id', 'integer', ['autoincrement' => true, 'unsigned' => true]);
        $this->table->addColumn('datecreated', 'datetime', ['notnull' => true]);
        $this->table->addColumn('datechanged', 'datetime', ['notnull' => true]);
        $this->table->addColumn('customer_id', 'integer', ['notnull' => false]);
        $this->table->addColumn('status', 'integer', ['notnull' => true, 'default' => 0]);
        $this->table->addColumn('session', 'string', ['notnull' => true, 'length' => 255]);
    }
    
    protected function addIndexes() {
    }
    
    protected function setPrimaryKey() {
        
        $this->table->setPrimaryKey(['id']);
    }
}
