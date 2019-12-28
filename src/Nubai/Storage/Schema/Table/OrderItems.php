<?php

namespace Bundle\Nubai\Storage\Schema\Table;

use Bolt\Storage\Database\Schema\Table\BaseTable;

/**
 * Description of OrderItems
 *
 * @author ricardo
 */
class OrderItems extends BaseTable {
    
    protected function addColumns() {
        
        $this->table->addColumn('id', 'integer', ['autoincrement' => true, 'unsigned' => true]);
        $this->table->addColumn('datecreated', 'datetime', ['notnull' => true]);
        $this->table->addColumn('datechanged', 'datetime', ['notnull' => true]);
        $this->table->addColumn('order_id', 'integer', ['notnull' => true]);
        $this->table->addColumn('product_id', 'integer', ['notnull' => true]);
        $this->table->addColumn('quantity', 'integer', ['notnull' => true]);
    }
    
    protected function addIndexes() {
        
        $this->table->addIndex(['order_id']);
    }
    
    protected function setPrimaryKey() {
        
        $this->table->setPrimaryKey(['id']);
    }
}
