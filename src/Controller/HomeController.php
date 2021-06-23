<?php

namespace App\Controller;

use App\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/home", name="contact_new", methods={"GET","POST"})
     */
    public function index(Request $request): Response
    {
            $booking = new Booking();


        if ($request->isXmlHttpRequest()) {
            $entityManager = $this->getDoctrine()->getManager();
            $booking->setName($request->get('reservation_name'));
            $booking->setEmail($request->get('reservation_email'));
            $booking->setDate($request->get('reservation_date'));
            $booking->setPhone($request->get('reservation_phone'));
            $entityManager->persist($booking);
            $entityManager->flush();

            return  new JsonResponse($booking);

        }

        return $this->render('home/index.html.twig', [
            'booking' => $booking]);
    }
}
