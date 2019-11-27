<?php

namespace Bundle\Nubai\Storage\Schema\Table;

use Bolt\Storage\Database\Schema\Table\BaseTable;

/**
 * Description of Customers
 *
 * @author ricardo
 */
class Customers extends BaseTable {
    
    protected function addColumns() {
        
        $this->table->addColumn('id', 'integer', ['autoincrement' => true, 'unsigned' => true]);
        $this->table->addColumn('datecreated', 'datetime', ['notnull' => true]);
        $this->table->addColumn('datechanged', 'datetime', ['notnull' => true]);
        $this->table->addColumn('email', 'string', ['notnull' => true, 'length' => 255]);
        $this->table->addColumn('forename', 'string', ['notnull' => true, 'length' => 255]);
        $this->table->addColumn('surname', 'string', ['notnull' => true, 'length' => 255]);
        $this->table->addColumn('password', 'string', ['notnull' => true, 'length' => 255]);
        $this->table->addColumn('company', 'string', ['notnull' => false, 'length' => 255]);
        $this->table->addColumn('address', 'string', ['notnull' => false, 'length' => 500]);
        $this->table->addColumn('phone', 'string', ['notnull' => false, 'length' => 10]);
        $this->table->addColumn('cell_phone', 'string', ['notnull' => false, 'length' => 10]);
        $this->table->addColumn('email_verification_code', 'string', ['notnull' => false, 'length' => 100]);
        $this->table->addColumn('password_recovery_code', 'string', ['notnull' => false, 'length' => 100]);
        $this->table->addColumn('activated', 'boolean', ['notnull' => true, 'default' => false]);
        $this->table->addColumn('locked', 'datetime', ['notnull' => false]);
    }
    
    protected function addIndexes() {
        
        $this->table->addIndex(['email']);
    }
    
    protected function setPrimaryKey() {
        
        $this->table->setPrimaryKey(['id']);
    }
}
