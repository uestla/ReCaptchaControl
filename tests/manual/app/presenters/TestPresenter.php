<?php

declare(strict_types = 1);

namespace App\Presenters;

use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Application\UI\Multiplier;


final class TestPresenter extends Presenter
{

	// === NON-AJAX FORMS ========================================

	protected function createComponentBasicForm(): Form
	{
		$form = static::factoryForm();
		$form->onSuccess[] = [$this, 'onFormSuccess'];
		return $form;
	}


	protected function createComponentMultiForm(): Multiplier
	{
		return new Multiplier(function ($name) {
			$form = static::factoryForm();
			$form->onSuccess[] = [$this, 'onFormSuccess'];
			return $form;
		});
	}


	public function onFormSuccess(Form $form): void
	{
		$this->flashMessage(sprintf('reCAPTCHA has been validated successfully! (form "%s")', $form->getName()), 'success');
		$this->redirect('this');
	}


	// === AJAX FORMS ========================================

	protected function createComponentAjaxForm(): Form
	{
		$form = static::factoryForm();

		// redraw form on each submit due to possible errors
		$form->onSubmit[] = function () {
			$this->redrawControl('form');
		};

		$form->onSuccess[] = [$this, 'onAjaxFormSuccess'];

		return $form;
	}


	protected function createComponentMultiAjaxForm(): Multiplier
	{
		return new Multiplier(function ($name) {
			$form = static::factoryForm();

			$form->onSubmit[] = function () use ($name) {
				// redraw form snippet just like in single AJAX form example
				$this->redrawControl('form-' . $name);
			};

			$form->onSuccess[] = [$this, 'onAjaxFormSuccess'];
			return $form;
		});
	}


	public function onAjaxFormSuccess(Form $form): void
	{
		$this->flashMessage(sprintf('reCAPTCHA has been validated successfully! (form "%s")', $form->getName()), 'success');
		$this->redrawControl('flashes');

		if (!$this->isAjax()) {
			$this->flashMessage('Non-AJAX request detected. The page has therefore been reloaded.', 'info');
			$this->redirect('this');
		}
	}


	// === AJAX FORMS ========================================

	protected function createComponentInvisibleForm(): Form
	{
		$form = static::factoryInvisibleForm();
		$form->onSuccess[] = [$this, 'onFormSuccess'];
		return $form;
	}


	protected function createComponentMultiInvisibleForm(): Multiplier
	{
		return new Multiplier(function ($name) {
			$form = static::factoryInvisibleForm();
			$form->onSuccess[] = [$this, 'onFormSuccess'];
			return $form;
		});
	}


	// === FORM FACTORY ========================================

	private static function factoryForm(): Form
	{
		$form = new Form;
		$form->addReCaptcha('recaptcha', 'reCAPTCHA for you', "Please prove you're not a robot.");
		$form->addSubmit('send', 'Submit form');
		return $form;
	}


	private static function factoryInvisibleForm(): Form
	{
		$form = new Form;

		$form->addText('email', 'Your e-mail')
			->setHtmlType('email')
			->setEmptyValue('@')
			->setRequired(true)
			->addRule(Form::EMAIL, 'Please provide a valid e-mail address.');

		$form->addReCaptcha('recaptcha', null, "Please prove you're not a robot.");
		$form->addSubmit('send', 'Submit form');

		return $form;
	}

}
