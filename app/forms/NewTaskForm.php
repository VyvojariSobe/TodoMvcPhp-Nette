<?php
declare(strict_types = 1);

namespace App\Forms;

use App\Model\TodosManager;
use Nette\Application\UI\Form;

class NewTaskForm
{
	/** @var TodosManager */
	private $todosManager;

	public function __construct(TodosManager $todosManager)
	{
		$this->todosManager = $todosManager;
	}

	public function create() : Form
	{
		$form = new Form();
		$form->addText('value')->addRule($form::FILLED);
		$form->onSuccess[] = [$this, 'process'];

		return $form;
	}

	public function process(Form $form)
	{
		$this->todosManager->add($form->values->value);
	}
}
