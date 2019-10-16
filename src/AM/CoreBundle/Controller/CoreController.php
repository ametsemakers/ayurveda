<?php

namespace AM\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use AM\AdminBundle\Form\ContactType;
// use AM\AdminBundle\Form\Handler\ContactHandler;
use AM\CoreBundle\SendMail\AMSendMail;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CoreController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('AMAdminBundle:Article');
        
        $blogPost = $repository->getMostRecentBlogPost();

        return $this->render('@AMCore/Default/homepage.html.twig', [
            'blogPost' => $blogPost
        ]);
    }

    /**
     * @Route("/homeAlt", name="homeAlt")
     */
    public function homeAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('AMAdminBundle:Article');
        $articles = $repository->findAll();

        return $this->render('@AMCore/Default/homeAlt.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/sante", name="sante")
     */
    public function santeAction()
    {
        return $this->render('@AMCore/Default/sante.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction(Request $request)
    {

        $form = $this->createForm(ContactType::class);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->handleRequest($request);
                
            $data = $form->getData();
            $message = \Swift_Message::newInstance()
                ->setSubject($form->get('subject')->getData())
                ->setFrom($form->get('name')->getData())
                ->setTo('srxoexyq@sharklasers.com')
                ->setBody(
                    $form->get('content')->getData(),
                    'text/plain'
                )
            ;

            $this->get('mailer')->send($message);
            
            //$aMSendMail->sendMail($form->getData());

            // if ($aMSendMail->sendMail($data)) {
            //         $session = $request->getSession();
            //         $session->getFlashBag()->add('notice', 'Merci de m\'avoir contacté, je répondrai dans les plus brefs délails.');

            //     return $this->redirectToRoute('homepage');
            // }
            // else {
            //         $session = $request->getSession();
            //         $session->getFlashBag()->add('notice', 'No cigar...');
            // }
        }
        

        return $this->render('@AMCore\Default\contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/histoire", name="histoire")
     */  
    public function histoireAction($id)
    {
        // Exemple d'utilisation -> à verifier

        $em = $this->getDoctrine()->getManager();

        // On récupère la position qui répresent la page $id
        $position = $em->getRepository('AMAdminBundle:Position')->find($id);

        if (null === $position) {
        throw new NotFoundHttpException("La position d'id ".$id." n'existe pas.");
        }

        // On récupère les articles pour cette position
        $articles = $em
        ->getRepository('AMAdminBundle:Article')
        ->findBy(array('position' => $position))
        ;

        return $this->render('@AMCore/Default/histoire.html.twig', array(
        'position' => $position,
        'articles' => $articles
        ));
    }

    /**
     * @Route("/blog", name="blog")
     */
    public function blogAction()
    {
        $em = $this->getDoctrine()->getManager();

        // La position des articles pour le blog est 9
        $blog = 9;

        $articles = $em
        ->getRepository('AMAdminBundle:Article')
        ->getDefinedArticles($blog)
        ;

        return $this->render('@AMCore/Default/blog.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/blog/{keyword}", name="blog_search", requirements={"keyword" = "^[a-zA-Z]*$"})
     */
    public function keywordAction($keyword)
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em
        ->getRepository('AMAdminBundle:Article')
        ->getArticlesByKeyword($keyword)
        ;

        return $this->render('@AMCore/Default/searchBlog.html.twig', [
            'articles' => $articles
        ]);
    }
}