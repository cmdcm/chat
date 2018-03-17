<?php
namespace concise\session\db;
use concise\Model;
class SessionDatabaseModel extends Model
{
	protected $table;
    public function __construct ($table)
    {
    	$this->table = $table;
    	parent::__construct();
    }
}