<?php

namespace App\Model;

use Nette;
use App;

/**
 * BaseRepository
 */
abstract class BaseRepository extends Nette\Object
{
	/** @var \Nette\Database\Context */
	protected $database;

	/** @var string */
	protected $table;


	/**
	 * Class constructor
	 * @param \Nette\Database\Context $database
	 * @throws \Nette\InvalidStateException
	 */
	public function __construct (Nette\Database\Context $database) {
		$this->database = $database;
		if ($this->table === NULL) {
			throw new App\Exceptions\InvalidStateException('Table name not set in ' . get_class($this) . '.');
		}
	}


	/**
	 * Return table.
	 * @return \Nette\Database\Table\Selection
	 */
	protected function getTable () {
		return $this->database->table($this->table);
	}


	/**
	 * Return single row with given primary key.
	 * @param integer $id
	 * @return Entity|null
	 */
	public function get ($id) {
		$row = $this->getTable()->get($id);
		return ($row instanceof Nette\Database\Table\ActiveRow) ? $this->toEntity($row) : null;
	}


	/**
	 * Return single row according to given parameters in $by array.
	 * ex. $by = array('name' => 'Martin')
	 * @param array $by
	 * @return Entity|null
	 */
	public function getOneBy ($by) {
		$rows = $this->getTable()->where($by)->fetchAll();
		if (count($rows) > 1) {
			throw new App\Exceptions\InvalidStateException('BaseRepository::getOneBy() error - Multiple results.');
		}
		return (count($rows)) ? $this->toEntity(array_pop($rows)) : null;
	}


	/**
	 * Return rows according to given parameters in $by array.
	 * ex. $by = array('name' => 'Martin')
	 * @param array $by
	 * @return array
	 */
	public function getBy ($by) {
		$rows = $this->getTable()->where($by)->fetchAll();
		return array_map(array($this, 'toEntity'), $rows);
	}


	/**
	 * Return all rows from the table.
	 * @return array
	 */
	public function getAll () {
		$rows = $this->getTable()->fetchAll();
		return array_map(array($this, 'toEntity'), $rows);
	}


	/**
	 *
	 */
	abstract protected function toEntity (Nette\Database\Table\ActiveRow $data);


	/**
	 * Insert new row.
	 * @param array $data
	 * @return Nette\Database\Table\IRow|integer|boolean
	 * @throws \PDOException
	 */
	protected function insert ($data) {
		try {
			$item = $this->getTable()->insert($data);
			return ($item instanceof Nette\Database\Table\ActiveRow) ? $this->toEntity($item) : null;
		}
		catch (\PDOException $e) {
			switch ($e->getCode()) {
				case '23000':
					throw new App\Exceptions\DuplicateEntryException($e->getMessage(), $e->getCode());
				default:
					throw new App\Exceptions\InvalidStateException($e->getMessage(), $e->getCode());
			}
		}
	}


	/**
	 * Update row with given primary key.
	 * @param integer $id
	 * @param array $data
	 * @throws App\Exceptions\InvalidStateException
	 */
	protected function update ($id, $data) {
		$item = $this->getTable()->get($id);
		if (null !== $item) {
			try {
				$item->update($data);
				$item = $this->getTable()->get($id);
				return ($item instanceof Nette\Database\Table\ActiveRow) ? $this->toEntity($item) : null;
			}
			catch (\PDOException $e) {
				switch ($e->getCode()) {
					case '23000':
						throw new App\Exceptions\DuplicateEntryException($e->getMessage(), $e->getCode());
					default:
						throw new App\Exceptions\InvalidStateException($e->getMessage(), $e->getCode());
				}
			}
		} else {
			throw new App\Exceptions\InvalidStateException('Požadovaný záznam neexistuje.');
		}
	}


	/**
	 * Delete row with given primary key.
	 * @param integer $id
	 */
	public function delete ($id) {
		$item = $this->getTable()->get($id);
		if (null !== $item) {
			$item->delete();
		} else {
			throw new App\Exceptions\InvalidStateException('Požadovaný záznam neexistuje.');
		}
	}


}
