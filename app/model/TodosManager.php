<?php
declare(strict_types = 1);

namespace App\Model;

use Nette\Database\Context;

class TodosManager
{
	const STATUS_ALL = NULL;
	const STATUS_ACTIVE = 0;
	const STATUS_COMPLETED = 1;
	/** @var Context */
	private $context;

	public function __construct(Context $context)
	{
		$this->context = $context;
	}

	protected function getTable()
	{
		return $this->context->table('todos');
	}

	public function fetchAll(int $status = NULL) : array
	{
		$table = $this->getTable()->order('timeCreated');

		if ($status !== NULL) {
			$table->where('isDone', $status);
		}

		return $table->fetchAll();
	}

	public function countActive() : int
	{
		return $this->getTable()->where('isDone', self::STATUS_ACTIVE)->count('id');
	}

	public function countCompleted() : int
	{
		return $this->getTable()->where('isDone', self::STATUS_COMPLETED)->count('id');
	}

	public function remove(int $id)
	{
		$this->getTable()->where('id', $id)->delete();
	}

	public function clearCompleted()
	{
		$this->getTable()->where('isDone', self::STATUS_COMPLETED)->delete();
	}

	public function changeStatus(int $id, int $status)
	{
		$this->getTable()->where('id', $id)->update(['isDone' => $status]);
	}

	public function changeValue(int $id, string $value)
	{
		$this->getTable()->where('id', $id)->update(['value' => $value]);
	}

	public function add(string $value)
	{
		$this->getTable()->insert(
			[
				'value'       => $value,
				'isDone'      => 0,
				'timeCreated' => time()
			]
		);
	}
}
