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
		if (   ($this->request->isPost())
			&& ($this->security->checkToken())
			&& ($this->session->get("security_code") == $this->request->getPost('comments_captcha'))
			&& ($this->request->getPost('comments_message'))) {
			
			$comments = new Comments();
			if($this->request->getPost('comments_name') == '') {
				$comments->name = 	'Аноним';
			}else{
				$comments->name = 	$this->request->getPost('comments_name');
			}
			$comments->message = 	$this->markdown->text($this->request->getPost('comments_message'));
			$comments->timestamp = 	time();
			$comments->post = 		$this->request->getPost('post');

			if (!$comments->save()) {
				foreach ($comments->getMessages() as $message) {
					$this->flashSession->error((string) $message);
					return $this->response->redirect("post/show/".$this->request->getPost('post'));
				}
			} else {
				$this->flashSession->success("Комментарий успешно добавлен");
				return $this->response->redirect("post/show/".$this->request->getPost('post'));
			}
		}elseif($this->session->get("security_code") != $this->request->getPost('comments_captcha')){

			$this->flashSession->error("Капча введена не верно");
			return $this->response->redirect("post/show/".$this->request->getPost('post'));

		}elseif(!$this->request->getPost('comments_message')){

			$this->flashSession->error("Сообщение должно быть написанно");
			return $this->response->redirect("post/show/".$this->request->getPost('post'));
		}
	}

	public function updateAction($id = 0)
	{

	}

	public function deleteAction($id = 0)
	{

	}

}