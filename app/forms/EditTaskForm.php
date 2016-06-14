<?php
declare(strict_types = 1);

namespace App\Forms;

use App\Model\TodosManager;
use Nette\Application\UI\Form;

class EditTaskForm
{
	/** @var TodosManager */
	private $todosManager;

	public function __construct(TodosManager $todosManager)
	{
		$this->todosManager = $todosManager;
	}

	public function create(int $id) : Form
	{
		$form = new Form();
		$form->addHidden('id', $id)->addRule($form::FILLED);
		$form->addText('value')->addRule($form::FILLED);
		$form->onSuccess[] = [$this, 'process'];

		return $form;
	}

	public function process(Form $form)
	{
		$this->todosManager->changeValue(+$form->values->id, $form->values->value);
	}
}
