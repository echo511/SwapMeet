<?php

namespace Echo511\SwapMeet\ORM;

use LeanMapper\DefaultMapper;
use LeanMapper\Row;


class Mapper extends DefaultMapper
{

	public function getEntityClass($table, Row $row = null)
	{
		return 'Echo511\SwapMeet\Entity\\' . ucfirst($table);
	}



}