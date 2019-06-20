<?php

namespace AM\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * ArticleRepository
 */
class ArticleRepository extends \Doctrine\ORM\EntityRepository
{
    public function getArticles($page, $nbPerPage)
    {
        $query = $this->createQueryBuilder('a')
            // Jointure l'attribut images
            ->leftJoin('a.images', 'i')
            ->addSelect('i')
            // Jointure l'attribut categories
            ->leftJoin('a.categories', 'c')
            ->addSelect('c')
            ->orderBy('a.date', 'DESC')
            ->leftJoin('a.position', 'p')
            ->addSelect('p')
            ->getQuery()
        ;

        $query
            ->setFirstResult(($page-1) * $nbPerPage)
            ->setMaxResults($nbPerPage)
        ;

        return new Paginator($query, true);
    }

    public function getArticleDetail($id)
    {
        $query = $this->createQueryBuilder('a')
            ->where('a.id = :id')
            ->leftJoin('a.images', 'i')
            ->addSelect('i')
            ->leftJoin('a.categories', 'c')
            ->addSelect('c')
            ->orderBy('a.date', 'DESC')
            ->leftJoin('a.position', 'p')
            ->addSelect('p')
            ->setParameter('id', $id)
            ->getQuery()
        ;

        $article = $query->getSingleResult();
        return $article;
    }

    public function getMostRecentBlogPost()
    {
        $query = $this->createQueryBuilder('a')
            ->orderBy('a.date', 'DESC')
            ->setMaxResults(1)
            ->getquery()
        ;

        $blogPost = $query->getOneOrNullResult();
        return $blogPost;
    }

    public function getDefinedArticles($pos)
    {
        $query = $this->createQueryBuilder('a')
            // Jointure l'attribut images
            ->where('a.position = :pos')
            ->leftJoin('a.images', 'i')
            ->addSelect('i')
            // Jointure l'attribut categories
            ->leftJoin('a.categories', 'c')
            ->addSelect('c')
            ->orderBy('a.date', 'DESC')
            ->leftJoin('a.position', 'p')
            ->addSelect('p')
            ->setParameter('pos', $pos)
            ->getQuery()
        ;

        $articles = $query->getResult();
        return $articles;
    }

    public function getArticlesByKeyword($keyword)
    {
        $query = $this->createQueryBuilder('a')
            // Jointure l'attribut images
            ->leftJoin('a.images', 'i')
            ->addSelect('i')
            // Jointure l'attribut categories
            ->leftJoin('a.categories', 'c')
            ->addSelect('c')
            ->orderBy('a.date', 'DESC')
            ->leftJoin('a.position', 'p')
            ->addSelect('p')
            ->where('c.name = :keyword')
            ->setParameter('keyword', $keyword)
            ->getQuery()
        ;

        $articles = $query->getResult();
        return $articles;
    }
}
