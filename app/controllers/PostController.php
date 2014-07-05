<?php
class PostController extends ControllerBase
{

	/**
	 * So if we want to check if the User has access to Post::createAction(),
	 * all we need to do is to check if matching session variable exists and contains
	 * expected value. (Keep in mind that this “authorization system” is very simple)
	 */
	public function beforeExecuteRoute($dispatcher)
	{

		// Действия, которые защищены законом
		$restricted = array('create', 'update', 'delete');

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

		$category = $this->dispatcher->getParam("category");

		if ($category) {
			$parameter = array(
				'category = :category:',
				'order' => 'id DESC',
				'bind' => array(
					'category' => $category
				)
			);
		} else {
			$parameter = array(
				'order' => 'id DESC'
			);
		}

		$currentPage = $this->request->getQuery('page', 'int');
		if ($currentPage <= 0) $currentPage = 1;

		$post = Post::find($parameter);

		$paginator = new \Phalcon\Paginator\Adapter\Model(
			array(
				'data' => $post,
				'limit'=> 9,
				'page' => $currentPage
			)
		);

		// Получение результатов работы пагинатора
		$post = $paginator->getPaginate();
		
		if (!$post->items) {
			return $this->dispatcher->forward(array(
				'controller' => 'pages',
				'action' => 'show404',
				'params' => array('type' => 'noPost')
			));
		}

		$this->view->setVar('posts', $post);

		$this->tag->prependTitle("Блог # ");
	}

	/**
	 * Let’s read that record from the database. When using MySQL adapter,
	 * like we do in this tutorial, $id variable will be escaped so
	 * we don’t have to deal with it.
	 */
	public function showAction($id = 0)
	{

		$post = Post::findFirst(array(
			'id = :id:',
			'bind' => array(
				'id' => $id
			)
		));

		if ((!$post) || ($id <= 0)) {
			return $this->dispatcher->forward(array(
				'controller' => 'pages',
				'action' => 'show404',
				'params' => array('type' => 'noPost')
			));
		}

		$comments = Comments::find(array(
			'post = :id:',
			'order' => 'id ASC',
			'bind' => array(
				'id' => $id
			)
		));

		$this->view->setVar('post', $post);
		$this->view->setVar('comments', $comments);
		
		$this->tag->prependTitle($post->subject . " # ");
	}

	public function createAction()
	{
		$this->tag->prependTitle("Создание записи # ");

		if ($this->request->isPost()) {
			$post = new Post();
			$post->subject = $this->request->getPost('post_subject');
			$post->message = $this->markdown->text($this->request->getPost('post_message'));
			$post->timestamp = time();
			$post->category = $this->request->getPost('post_category');
			$post->comments_enabled = $this->request->getPost('post_comments');

			if (!$post->save()) {
				foreach ($post->getMessages() as $message) {
					$this->flash->error((string) $message);
				}
				return $this->dispatcher->forward(array(
					'controller' => 'post',
					'action' => 'create'
				));
			} else {
				$this->flash->success("Запись успешно создана");
				return $this->dispatcher->forward(array(
					'controller' => 'post',
					'action' => 'index'
				));
			}
		}
	}

	public function updateAction($id = 0)
	{
		if ($this->request->isPost()) {
			$post = Post::findFirst($id);
			$post->name = "RoboCop";
			$post->save();
		}
	}

	public function deleteAction($id = 0)
	{

		$post = Post::findFirst(array(
			'id = :id:',
			'bind' => array('id' => $id)
		));
		if (!$post) {
			$this->flash->error("Запись не найдена");
			return $this->dispatcher->forward(array(
				'controller' => 'post',
				'action' => 'index'
			));
		}

		if (!$post->delete()) {
			foreach ($post->getMessages() as $message){
				$this->flash->error((string) $message);
			}
			return $this->dispatcher->forward(array(
				'controller' => 'post',
				'action' => 'index'
			));
		} else {
			$this->flash->success("Запись успешно удалена");
			return $this->dispatcher->forward(array(
				'controller' => 'post',
				'action' => 'index'
			));
		}
	}

	/**
	 * Select posts by category
	 * Need peremestit' nahuy too
	 */
	public function categoryAction($category = '')
	{
		$category = $this->filter->sanitize($category, "alphanum");

		$this->tag->prependTitle($category . " # ");

		return $this->dispatcher->forward(array(
			'controller' => 'post',
			'action' => 'index',
			'params' => array('category' => $category)
		));

	}

}