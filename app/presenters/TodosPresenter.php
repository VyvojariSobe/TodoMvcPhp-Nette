<?php
declare(strict_types = 1);

namespace App\Presenters;

use App\Forms\EditTaskForm;
use App\Forms\NewTaskForm;
use App\Model\TodosManager;
use Nette;
use Nette\Application\UI\Form;
use Nette\Application\UI\Multiplier;
use Nette\Application\UI\Presenter;

class TodosPresenter extends Presenter
{
	/** @var TodosManager @inject */
	public $todosManager;
	/** @var NewTaskForm @inject */
	public $newTaskForm;
	/** @var EditTaskForm @inject */
	public $editTaskForm;

	protected function createComponentNewForm()
	{
		$form = $this->newTaskForm->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Task created', 'success');
			$this->redirect('default');
		};
		$form->onError[] = function () {
			$this->flashMessage("Task can't be empty", 'error');
			$this->redirect('default');
		};
		return $form;
	}

	protected function createComponentEditForm()
	{
		return new Multiplier(
			function (string $id) : Form {
				$form = $this->editTaskForm->create(+$id);
				$form->onSuccess[] = function () {
					$this->flashMessage('Task updated', 'success');
					$this->redirect('default');
				};
				$form->onError[] = function () {
					$this->flashMessage("Task can't be empty", 'error');
					$this->redirect('default');
				};
				return $form;
			}
		);
	}

	public function actionDefault(string $filter = '')
	{
		$filters = [
			''          => ['All', TodosManager::STATUS_ALL],
			'active'    => ['Active', TodosManager::STATUS_ACTIVE],
			'completed' => ['Completed', TodosManager::STATUS_COMPLETED],
		];

		if (empty($filters[$filter])) {
			$this->redirect('this', ['filter' => '']);
		}

		$this->template->activeFilter = $filter;
		$this->template->filters = $filters;

		$this->template->todos = $this->todosManager->fetchAll($filters[$filter][1]);
		$this->template->countActive = $this->todosManager->countActive();
		$this->template->countCompleted = $this->todosManager->countCompleted();
	}

	public function actionRemove(int $id)
	{
		$this->todosManager->remove($id);
		$this->flashMessage('Task removed', 'success');
		$this->redirect('default');
	}

	public function actionClearCompleted()
	{
		$this->todosManager->clearCompleted();
		$this->flashMessage('All completed tasks are removed', 'success');
		$this->redirect('default');
	}

	public function actionChangeStatus(int $id, string $status)
	{
		$newStatus = TodosManager::STATUS_ACTIVE;

		if ($status === 'check') {
			$newStatus = TodosManager::STATUS_COMPLETED;
		}

		$this->todosManager->changeStatus($id, $newStatus);
		$this->flashMessage('Task status changed', 'success');
		$this->redirect('default');
	}
}
