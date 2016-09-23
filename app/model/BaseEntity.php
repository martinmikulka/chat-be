<?php

namespace App\Model;

use Nette;

/**
 * BaseEntity
 */
abstract class BaseEntity extends Nette\Object
{
	/** @var \Nette\database\Table\ActiveRow */
	public $activeRow;


	/**
	 * Load data from ActiveRow
	 * @param Nette\Database\Table\ActiveRow $item
	 * @return \App\Model\*
	 */
	public function loadData(Nette\Database\Table\ActiveRow $data)
	{
		$this->activeRow = $data;
		foreach ($data as $prop => $value) {
			$this->$prop = $value;
		}
	}


}
