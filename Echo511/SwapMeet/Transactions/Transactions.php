<?php

namespace Echo511\SwapMeet\Transactions;

use Exception;
use Nette\Object;


/**
 * Transactions manager.
 * 
 * Needed to manage nested transactions in application? No problem.
 * When you have a facade method that does several tasks and that method should
 * be atomic and some tasks can be invoken from elsewhere thus they need to be
 * atomic too, then you need to have something that would track if all
 * transactions are done and only then send COMMIT to database.
 * 
 * @author Nikolas Tsiongas
 */
class Transactions extends Object
{

	/** @var callable */
	public $onFirst;

	/** @var callable */
	public $onLast;

	/** @var callable */
	public $onAbort;

	/** @var bool */
	private $allDone = true;

	/** @var string */
	private $firstTransaction;


	/**
	 * Start transaction.
	 * @param string $name recommended to use classname as name
	 */
	public function startTransaction($name)
	{
		if (!isset($this->firstTransaction)) {
			$this->onFirst($name);
			$this->firstTransaction = $name;
			$this->allDone = false;
		}
	}



	/**
	 * End transaction with name.
	 * @param string $name
	 */
	public function endTransaction($name)
	{
		if ($name == $this->firstTransaction) {
			$this->onLast($name);
			$this->firstTransaction = null;
			$this->allDone = true;
		}
	}



	/**
	 * Abort any transaction in stack.
	 */
	public function abortTransaction()
	{
		$this->onAbort();
		unset($this->firstTransaction);
		$this->allDone = false;
	}



	/**
	 * Has been all transaction successfully carried out?
	 * @return bool
	 */
	public function isAllDone()
	{
		if ($this->allDone === null) {
			throw new Exception('No transaction started.');
		}

		return $this->allDone;
	}



}