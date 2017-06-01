<?php

namespace Article\Repository;

use Article\Entity\Hydrator\CategoryHydrator;
use Article\Entity\Hydrator\PostHydrator;
use Article\Entity\Post;
use Zend\Db\Adapter\AdapterAwareTrait;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\Hydrator\Aggregate\AggregateHydrator;

class PostRepositoryImpl implements PostRepository
{
    use AdapterAwareTrait;

    public function save(Post $post)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->insert()
            ->values(array(
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
                'category_id' => $post->getCategory()->getId(),
                'created' => time(),
            ))
            ->into('post');

        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
    }

    public function fetch($page)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select();
        $select->columns(array(
                'id',
                'title',
                'content',
                'created',
            ))
            ->from(array('p' => 'post'))
            ->join(
                array('c' => 'category'), // Table name
                'c.id = p.category_id', // Condition
                array('category_id' => 'id', 'name'), // Columns
                $select::JOIN_INNER
            )
            ->order('p.created DESC')
            ->group('c.name');

        $hydrator = new AggregateHydrator();
        $hydrator->add(new PostHydrator());
        $hydrator->add(new CategoryHydrator());

        $resultSet = new HydratingResultSet($hydrator, new Post());
        $paginatorAdapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->adapter, $resultSet);
        $paginator = new \Zend\Paginator\Paginator($paginatorAdapter);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(10);

        return $paginator;
    }

    /**
     * @param $postId int
     *
     * @return Post|null
     */
    public function findById($postId)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select();
        $select->columns(array(
            'id',
            'title',
            'content',
            'created',
        ))
            ->from(array('p' => 'post'))
            ->join(
                array('c' => 'category'), // Table name
                'c.id = p.category_id', // Condition
                array('category_id' => 'id', 'name'), // Columns
                $select::JOIN_INNER
            )->where(array(
                'p.id' => $postId,
            ));

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $hydrator = new AggregateHydrator();
        $hydrator->add(new PostHydrator());
        $hydrator->add(new CategoryHydrator());

        $resultSet = new HydratingResultSet($hydrator, new Post());
        $resultSet->initialize($results);

        return ($resultSet->count() > 0 ? $resultSet->current() : null);
    }

    /**
     * @param Post $post
     *
     * @return void
     */
    public function update(Post $post)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->update('post')
            ->set(array(
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
                'category_id' => $post->getCategory()->getId(),
            ))
            ->where(array(
                'id' => $post->getId(),
            ));

        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
    }

    /**
     * @param $postId int
     *
     * @return void
     */
    public function delete($postId)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $delete = $sql->delete()
            ->from('post')
            ->where(array(
                'id' => $postId,
            ));

        $statement = $sql->prepareStatementForSqlObject($delete);
        $statement->execute();
    }
}