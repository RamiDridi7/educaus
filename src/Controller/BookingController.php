<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Calendar;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/booking")
 */
class BookingController extends AbstractController
{


    /**
     * @Route("/", name="booking_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $booking = new Calendar();

        if ($request->isXmlHttpRequest()) {
            $entityManager = $this->getDoctrine()->getManager();
            $booking->setTitle('Rdv with : '.$request->get('form_name'));
            $booking->setDescription('Email '.$request->get('form_email').'\n Phone : '.$request->get('form_phone'));
            $booking->setStart(new \DateTime($request->get('form_appointment_date').' '.$request->get('form_appointment_time')));
            $booking->setEnd(new \DateTime($request->get('form_appointment_date')));
            $booking->setAllDay(false);
            $booking->setTextColor('#fff');
            $booking->setBorderColor('rgb(0, 86, 179)');
            $booking->setBackgroundColor('rgb(0, 86, 179)');
            $entityManager->persist($booking);
            $entityManager->flush();

            return  new JsonResponse($booking);
        }

        return $this->render('booking/new.html.twig', [
            'booking' => $booking]);
    }


    /**
     * @Route("/{id}", name="booking_show", methods={"GET"})
     */
    public function show(Booking $booking): Response
    {
        $book = $this->getDoctrine()->getRepository(Booking::class)->findAll();
        return  new JsonResponse($book);
    }


    /**
     * @Route("/{id}/edit", name="booking_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Booking $booking): Response
    {
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('booking_index');
        }

        return $this->render('booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="booking_delete", methods={"POST"})
     */
    public function delete(Request $request, Booking $booking): Response
    {
        if ($this->isCsrfTokenValid('delete'.$booking->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($booking);
            $entityManager->flush();
        }

        return $this->redirectToRoute('booking_index');
    }
}
