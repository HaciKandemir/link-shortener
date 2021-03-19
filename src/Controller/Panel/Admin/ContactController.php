<?php

namespace App\Controller\Panel\Admin;

use App\Entity\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/panel/admin/contact", name="panel_admin_contact")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository(Mail::class)->findAll();
        return $this->render('panel/admin/contact/index.html.twig', [
            'messages' => $messages,
        ]);
    }
    /**
     * @Route("/panel/admin/contact/read/{id}", name="panel_contact_read")
     */
    public function read(int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $message = $em->getRepository(Mail::class)->find($id);
        $message->setIsReaded(true);
        $em->flush();
        return $this->render('panel/admin/contact/read.html.twig', [
            'message' => $message,
        ]);
    }

    /**
     * @Route("/panel/admin/contact/delete/{id}", name="panel_contact_delete")
     */
    public function delete(int $id): Response
    {
        $em = $this->getDoctrine()->getManager();

        $message = $em->getRepository(Mail::class)->find($id);
        $em->remove($message);
        $em->flush();
        $this->addFlash(
            'success',
            'Message deleted!'
        );

        return $this->redirectToRoute('panel_admin_contact');
    }
}
