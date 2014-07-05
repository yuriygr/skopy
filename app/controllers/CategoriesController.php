<?php

use \Phalcon\Tag as Tag,
    \Phalcon\Mvc\Model\Criteria;

class CategoriesController extends ControllerBase
{

    public function indexAction()
    {
        $this->session->conditions = null;
    }

    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Categories", $_POST);
            $this->session->conditions = $query->getConditions();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
            if ($numberPage <= 0) {
                $numberPage = 1;
            }
        }

        $categories = Categories::find($parameters);
        if (count($categories) == 0) {
            $this->flash->notice("Поиск не нашёл категорий");
            return $this->dispatcher->forward(array(
                "controller" => "categories",
                "action" => "index"
            ));
        }

        $paginator = new \Phalcon\Paginator\Adapter\Model(array(
            "data" => $categories,
            "limit"=> 10,
            "page" => $numberPage
        ));
        $page = $paginator->getPaginate();

        $this->view->setVar("page", $page);
    }

    public function newAction()
    {

    }

    public function editAction($id)
    {

        $request = $this->request;
        if (!$request->isPost()) {

            $categories = Categories::findFirst(array(
                'id = :id:',
                'bind' => array('id' => $id)
            ));
            if (!$categories) {
                $this->flash->error("Категории не существует");
                return $this->dispatcher->forward(array(
                    "controller" => "categories",
                    "action" => "index"
                ));
            }
            $this->view->setVar("id", $categories->id);

            Tag::displayTo("id", $categories->id);
            Tag::displayTo("name", $categories->name);
            Tag::displayTo("abbr", $categories->abbr);
        }
    }

    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "categories",
                "action" => "index"
            ));
        }

        $categories = new Categories();
        $categories->id = $this->request->getPost("id");
        $categories->name = $this->request->getPost("name");
        $categories->abbr = $this->request->getPost("abbr");
        
        if (!$categories->save()) {
            foreach ($categories->getMessages() as $message) {
                $this->flash->error((string) $message);
            }
            return $this->dispatcher->forward(array(
                "controller" => "categories",
                "action" => "new"
            ));
        } else {
            $this->flash->success("Категория успешно создана");
            return $this->dispatcher->forward(array(
                "controller" => "categories",
                "action" => "index"
            ));
        }

    }

    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "categories",
                "action" => "index"
            ));
        }

        $category = Categories::findFirst(array(
            'id = :id:',
            'bind' => array('id' => $this->request->getPost("id"))
        ));
        if (!$category) {
            $this->flash->error("Категория не существует");
            return $this->dispatcher->forward(array(
                "controller" => "categories",
                "action" => "index"
            ));
        }

        $categories->id = $this->request->getPost("id");
        $categories->name = $this->request->getPost("name");
        $categories->abbr = $this->request->getPost("abbr");

        if (!$categories->save()) {
            foreach ($categories->getMessages() as $message) {
                $this->flash->error((string) $message);
            }
            return $this->dispatcher->forward(array(
                "controller" => "categories",
                "action" => "edit",
                "params" => array($categories->id)
            ));
        } else {
            $this->flash->success("Категория успешно обновленна");
            return $this->dispatcher->forward(array(
                "controller" => "categories",
                "action" => "index"
            ));
        }

    }

    public function deleteAction($id)
    {

        $categories = Categories::findFirst(array(
            'id = :id:',
            'bind' => array('id' => $id)
        ));
        if (!$categories) {
            $this->flash->error("Категория не найдена");
            return $this->dispatcher->forward(array(
                'controller' => 'categories',
                'action' => 'index'
            ));
        }

        if (!$categories->delete()) {
            foreach ($categories->getMessages() as $message){
                $this->flash->error((string) $message);
            }
            return $this->dispatcher->forward(array(
                'controller' => 'categories',
                'action' => 'search'
            ));
        } else {
            $this->flash->success("Категория успешно удалена");
            return $this->dispatcher->forward(array(
                'controller' => 'categories',
                'action' => 'index'
            ));
        }
    }

}
