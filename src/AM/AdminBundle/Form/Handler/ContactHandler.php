<?php

namespace AM\AdminBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class ContactHandler
{
    protected $request;
    protected $form;
    protected $mailer;

    /**
     * @param Form $form
     * @param Request $request
     * @param $mailer
     */
    public function __construct(Form $form, Request $request, $mailer)
    {
        $form = $this->form;
        $request = $this->request;
        $mailer = $this->mailer;
    }

    /**
     * Process form
     * 
     * @return boolean
     */
    public function process($form)
    {
        if ($form->isSubmitted() && $form->isValid())
        {
            //$this->form->bindRequest($this->request);
            $form->handleRequest($request);

            $data = $this->form->getData();
            $this->onSuccess($data);

            return true;
        }
        return false;
    }

    protected function onSuccess($data)
    {
        $message = \Swift_Message::newInstance()
            ->setContentType('text/html')
            ->setSubject($data['subject'])
            ->setFrom($data['email'])
            ->setTo('monadresse@mail.fr')
            ->setBody($data['content'])
        ;

        $this->mailer->send($message);
    }

}