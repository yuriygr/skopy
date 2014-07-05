<?php
class CommentsController extends ControllerBase
{

	/**
	 * So if we want to check if the User has access to Post::createAction(),
	 * all we need to do is to check if matching session variable exists and contains
	 * expected value. (Keep in mind that this “authorization system” is very simple)
	 */
	public function beforeExecuteRoute($dispatcher)
	{

		// Действия, которые защищены законом
		$restricted = array('update', 'delete');

		//auth token
		$auth = $this->session->get('auth');

		//we check here if currently invoked action is restricted and if
		//the user is logged in
		if (in_array($dispatcher->getActionName(), $restricted) && !$auth) {

			$this->flash->error("У вас нет доступа к этому модулю");
			return $this->dispatcher->forward(array(
				'controller' => 'index',
				'action' => 'index'
			));
		}
	}

	/**
	 * We simply pass all the Post created to the view
	 */
	public function indexAction()
	{

	}

	public function addAction()
	{
		if (($this->request->isPost()) && ($this->security->checkToken())) {
			$comments = new Comments();
			$comments->name = $this->request->getPost('comments_name');
			$comments->tripcode = $this->request->getPost('comments_name');
			$comments->message = $this->markdown->text($this->request->getPost('comments_message'));
			$comments->timestamp = time();
			$comments->post = $this->request->getPost('post');

			if (!$comments->save()) {
				foreach ($comments->getMessages() as $message) {
					$this->flash->error((string) $message);
				}
				return $this->dispatcher->forward(array(
					'controller' => 'post',
					'action' => 'show',
					'id' => $comments->post
				));
			} else {
				$this->flash->success("Комментарий успешно добавлен");
				return $this->dispatcher->forward(array(
					'controller' => 'post',
					'action' => 'show',
					'id' => $comments->post
				));
			}
		}
	}

	public function updateAction($id = 0)
	{

	}

	public function deleteAction($id = 0)
	{

	}

}