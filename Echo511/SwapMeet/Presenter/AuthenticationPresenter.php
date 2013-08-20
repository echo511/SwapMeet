<?php

namespace Echo511\SwapMeet\Presenter;

use Nette\Application\UI\Form;


class AuthenticationPresenter extends BasePresenter
{

	public function createComponentLoginForm()
	{
		$form = new Form;

		$form->addText('username');
		$form->addPassword('password');
		$form->addSubmit('submit');
		$form->onSuccess[] = callback($this, 'processLoginForm');

		return $form;
	}



	public function processLoginForm(Form $form)
	{
		$parameters = new \Echo511\Security\AuthenticatorParameters(array(
		    'username' => $form->values->username,
		    'password' => $form->values->password,
		));

		$response = $this->user->login('database', $parameters);

		if ($response === true) {
			// @TODO:translation
			$this->flashMessage('Byl(a) jste přihlášen(a).', 'success');
		} else {
			$this->flashMessage('Chyba v údajích.', 'error');
		}
	}



	public function actionLogout()
	{
		$response = $this->user->logout();

		if ($response === true) {
			$this->flashMessage('Byl(a) jste odhlášen(a).', 'success');
		}
		$this->redirect(':SwapMeet:Browser:default');
	}



}