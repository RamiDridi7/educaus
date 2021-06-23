<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/event")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/", name="event_index", methods={"GET"})
     */
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="event_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $event = new Event();

        if ($request->isXmlHttpRequest()) {
            $entityManager = $this->getDoctrine()->getManager();
            $event->setTitle($request->get('title'));
            $event->setBackgroundColor($request->get('background_color'));
            $event->setBorderColor($request->get('border_color'));
            $event->setTextColor($request->get('text_color'));
            $entityManager->persist($event);
            $entityManager->flush();

            return  new JsonResponse($event);

        }



    }

    /**
     * @Route("/{id}", name="event_show", methods={"GET"})
     */
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="event_edit", methods={"PUT"})
     */
    public function edit(Request $request, Calendar $event): Response
    {


        if ($request->isXmlHttpRequest()) {
            $entityManager = $this->getDoctrine()->getManager();
            $event->setTitle($request->get('title'));

            try {
                $event->setStart(new DateTime($request->get('start')));
            } catch (\Exception $e) {
                var_dump($e);
            }
            try {
                $event->setEnd(new DateTime($request->get('end')));
            } catch (\Exception $e) {
                var_dump($e);
            }
            $event->setBackgroundColor($request->get('backgroundColor'));
            $event->setBorderColor($request->get('borderColor'));
            $event->setTextColor($request->get('textColor'));
            $event->setAllDay($request->get('allDay'));


            if($request->get('id') !== $event->getId() ){
                $entityManager->persist($event);
            }
            $entityManager->flush();

            return  new JsonResponse($event);

        }
    }

    /**
     * @Route("/{id}", name="event_delete", methods={"POST"})
     */
    public function delete(Request $request, Event $event): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('event_index');
    }
}
