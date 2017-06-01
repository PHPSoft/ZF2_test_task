<?php

namespace Article\Controller;

use Article\Entity\Hydrator\CategoryHydrator;
use Article\Entity\Hydrator\PostHydrator;
use Article\Entity\Post;
use Article\Form\Add;
use Article\Form\Edit;
use Article\InputFilter\AddPost;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\Hydrator\Aggregate\AggregateHydrator;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel(array(
            'paginator' => $this->getArticleService()->fetch($this->params()->fromRoute('page')),
        ));
    }

    public function addAction()
    {
        $form = new Add();
        $variables = array('form' => $form);

        if ($this->request->isPost()) {
            $articlePost = new Post();
            $form->bind($articlePost);
            $form->setInputFilter(new AddPost());
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                $this->getArticleService()->save($articlePost);
                $this->flashMessenger()->addSuccessMessage('The post has been added!');
            }
        }

        return new ViewModel($variables);
    }

    public function editAction()
    {
        $form = new Edit();

        if ($this->request->isPost()) {
            $post = new Post();
            $form->bind($post);
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                $this->getArticleService()->update($post);
                $this->flashMessenger()->addSuccessMessage('The post has been updated!');
            }
        } else {
            $post = $this->getArticleService()->findById($this->params()->fromRoute('postId'));

            if ($post == null) {
                $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
            } else {
                $form->bind($post);
                $form->get('category_id')->setValue($post->getCategory()->getId());
                $form->get('slug')->setValue($post->getSlug());
                $form->get('id')->setValue($post->getId());
            }
        }

        return new ViewModel(array(
            'form' => $form,
        ));
    }

    public function deleteAction()
    {
        $this->getArticleService()->delete($this->params()->fromRoute('postId'));
        $this->flashMessenger()->addSuccessMessage('The post has been deleted!');
        return $this->redirect()->toRoute('article');
    }

    /**
     * @return \Article\Service\ArticleService $articleService
     */
    protected function getArticleService()
    {
        return $this->getServiceLocator()->get('Article\Service\ArticleService');
    }
} 