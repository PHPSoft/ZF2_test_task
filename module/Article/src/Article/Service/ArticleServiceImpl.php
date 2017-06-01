<?php

namespace Article\Service;

use Article\Entity\Post;

class ArticleServiceImpl implements ArticleService
{
    /**
     * @var \Article\Repository\PostRepository $postRepository
     */
    protected $postRepository;


    /**
     * Saves a article post
     *
     * @param Post $post
     *
     * @return Post
     */
    public function save(Post $post)
    {
        $this->postRepository->save($post);
    }

    /**
     * @param $page int
     *
     * @return \Zend\Paginator\Paginator
     */
    public function fetch($page)
    {
        return $this->postRepository->fetch($page);
    }

    /**
     * @param $postId int
     *
     * @return Post|null
     */
    public function findById($postId)
    {
        return $this->postRepository->findById($postId);
    }

    /**
     * @param Post $post
     *
     * @return void
     */
    public function update(Post $post)
    {
        $this->postRepository->update($post);
    }

    /**
     * @param $postId int
     *
     * @return void
     */
    public function delete($postId)
    {
        $this->postRepository->delete($postId);
    }

    /**
     * @param \Article\Repository\PostRepository $postRepository
     */
    public function setArticleRepository($postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @return \Article\Repository\PostRepository
     */
    public function getArticleRepository()
    {
        return $this->postRepository;
    }
}