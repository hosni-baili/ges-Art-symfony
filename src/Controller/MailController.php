<?php

namespace App\Controller;

use SebastianBergmann\Environment\Console;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MailController extends AbstractController
{
    /**
     * @Route("/mail", name="mail")
     */
    public function index(): Response
    {
        return $this->render('mail/index.html.twig', [
            'controller_name' => 'MailController',
        ]);
    }


    /** 
     *  @Route("/sendmail", name="sendmail") 
     * */ 
    public function testMail(\Swift_Mailer $mailer, Request $request)
    {
    
        if($request->getMethod()=="POST")
        {
            $attachedFile = false;

                $emailTo = $request->get('emailTo');
                $sujet = $request->get('sujet');
                $message = $request->get('message');
                $attachedFile= $request->get('attach');
                // $uploadedFile = $request->files->get('attach');
                // $attachedFile = $uploadedFile->getPath() . DIRECTORY_SEPARATOR . $uploadedFile->getClientOriginalName();
                
                $messageToSend = (new \Swift_Message($sujet))
                ->setFrom('hosnibaili1987@gmail.com')
                ->setTo($emailTo)
                ->setBody($message,'text/plain');
                //->setBody($this->renderView('mail/registration.html.twig',['msg' =>  $message]),'text/html');
               // $message->attach(\Swift_Attachment::fromPath($attachedFile));
                $mailer->send($messageToSend);
                return $this->redirectToRoute('sendmail');
        }
        return $this->render('mail/compose.html.twig');
    }

}

