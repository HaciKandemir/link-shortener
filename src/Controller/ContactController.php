<?php

namespace App\Controller;

use App\Entity\Mail;
use App\Form\MailFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request): Response
    {
        $mail = new Mail();
        $form = $this->createForm(MailFormType::class, $mail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mail = $form->getData();
            $mail->setIsReaded(false);
            $mail->setIsAnswered(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($mail);
            $em->flush();

            $this->addFlash(
                'success',
                'Mesajınız gönderildi!'
            );

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
