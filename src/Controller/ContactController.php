<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Calendar;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\CalendarRepository;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @Route("/contact")
 */
class ContactController extends AbstractController
{


    /**
     * @Route("/new", name="contact_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $contact = new Contact();


        if ($request->isXmlHttpRequest()) {
            $entityManager = $this->getDoctrine()->getManager();
            $contact->setName($request->get('name'));
            $contact->setEmail($request->get('email'));
            $contact->setMessage($request->get('message'));
            $contact->setPhone($request->get('phone'));
            $contact->setSubject($request->get('subject'));
            $entityManager->persist($contact);
            $entityManager->flush();

            return  new JsonResponse($contact);

        }

        return $this->render('contact/new.html.twig', [
            'contact' => $contact]);
    }


    public function show(): Response
    {
        $contactList = $this->getDoctrine()->getRepository(Contact::class)->findAll();
        return $this->render('contact/show.html.twig', [
            'contacts' => $contactList,
        ]);
    }

    public function compose(): Response
    {
        $contactList = $this->getDoctrine()->getRepository(Contact::class)->findAll();
        return $this->render('contact/composeContact.html.twig', [
            'contacts' => $contactList,
        ]);
    }
    public function read(string $id): Response
    {
        $receiver = $this->getDoctrine()->getRepository(Contact::class)->find($id);
        return $this->render('contact/readContact.html.twig', [
            'receiver' => $receiver,
        ]);
    }
    /**
     * @Route("/{id}/edit", name="contact_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Contact $contact): Response
    {
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contact_index');
        }

        return $this->render('contact/edit.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contact_delete", methods={"POST"})
     */
    public function delete(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $data =   json_decode($request->getContent());

        if ($request->isXmlHttpRequest()) {
            foreach ($data as $id) {
                $con = $entityManager->getRepository(Contact::class)->find($id);
                $entityManager->remove($con);
                $entityManager->flush();
            }

        }
        return  new JsonResponse($data);


    }

    public function sendEmail(MailerInterface $mailer,string $adrs): Response
    {
        try {
        $email = (new Email())
            ->from('rami.dridi1@esprit.tn')
            ->to($adrs)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');


            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }
        return  new JsonResponse($email);

        // ...
    }
}
