<?php

namespace AM\AdminBundle\Controller;

use AM\AdminBundle\Entity\Article;
use AM\AdminBundle\Form\ArticleType;
use AM\AdminBundle\Form\ArticleEditType;
use AM\AdminBundle\Form\ArticleDeleteType;
use AM\AdminBundle\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/{page}", name="am_admin_home", requirements={"page" = "\d+"})
     */
    public function indexAction($page)
    {
        if ($page < 1) {
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        $nbPerPage = 3;

        $repository = $this->getDoctrine()->getManager()->getRepository('AMAdminBundle:Article');
        $articles = $repository->getArticles($page, $nbPerPage);

        $nbPages = ceil(count($articles) / $nbPerPage);

        //if ($page > $nbPages) {
        //    throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        //}

        return $this->render('@AMAdmin/Default/listArticles.html.twig', [
            'articles' => $articles,
            'nbPages'  => $nbPages,
            'page'     => $page,
        ]);
    }

    /**
     * @Route("/view/{id}", name="am_admin_view", requirements={"id" = "\d+"})
     */
    public function viewAction($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('AMAdminBundle:Article');
        //$article = $repository->find($id);
        $article = $repository->getArticleDetail($id);
        

        //$repositoryPosition = $this->getDoctrine()->getManager()->getRepository('AMAdminBundle:Position');
        //$position = $repositoryPosition->findAll();

        if (null === $article) {
            throw new NotFoundHttpException("L'article d'id ".$id." n'existe pas.");
        }

        return $this->render('@AMAdmin/Default/viewArticle.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/add", name="am_admin_add")
     */
    public function addAction(Request $request,UserInterface $user)
    {
        // On récupère le service
        // $antispam = $this->container->get('am_admin.antispam');

        // Je pars du principe que $text contient le texte d'un message quelconque
        // $text = '...';
        // if ($antispam->isSpam($text)) {
        //     throw new \Exception('Votre message a été détecté comme spam !');
        // }
    
        // Ici le message n'est pas un spam

        //if (!$this->get('security.authorization_checker')->isGranted('ROLE_AUTEUR')) {
        //    throw new AccessDeniedException('Accès limité aux auteurs.');
        //}

        $article = new Article();
        $author = $this->getUser();
        $article->setAuthor($author);
        $form    = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Article bien enregistrée.');

            return $this->redirectToRoute('am_admin_home', [
                'page' => 1,
            ]);
        }
        // temp
        return $this->render('@AMAdmin/Default/addArticle.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="am_admin_edit", requirements={"id" = "\d+"})
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository('AMAdminBundle:Article')->find($id);

        if (null === $article) {
            throw new NotFoundException("L'article d'id ".$id." n'existe pas.");
        }

        $form = $this->createForm(ArticleEditType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Article modifiée');

            // return $this->redirectToRoute('am_admin_view', [
            //     'id' => $advert->getId()
            // ]);
            return $this->redirectToRoute('am_admin_home', [
                'page' => 1,
            ]);
        }

        
        return $this->render('@AMAdmin/Default/editArticle.html.twig', [
            'article' => $article,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="am_admin_delete", requirements={"id" = "\d+"})
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository('AMAdminBundle:Article')->find($id);

        if (null === $article) {
            throw new NotFoundException("'L'article d'id ".$id." n'existe pas.");
        }

        $form = $this->get('form.factory')->create();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($article);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', "L'article a été supprimée.");

            return $this->redirectToRoute('am_admin_home', [
                'page' => 1,
            ]);
        }
        return $this->render('@AMAdmin/Default/deleteArticle.html.twig', [
            'article' => $article,
            'form'    => $form->createView(),
        ]);
    }
}
