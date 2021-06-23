<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Entity\Event;
use App\Form\CalendarType;
use App\Repository\CalendarRepository;
use App\Repository\EventRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/calendar")
 */
class CalendarController extends AbstractController
{
    /**
     * @Route("/", name="calendar_index", methods={"GET"})
     */
    public function index(CalendarRepository $calendar,EventRepository $event): Response
    {
        $events = $calendar->findAll();
        $ev = $event->findAll();
        $rdvs = [];
        foreach($events as $event)
        {
            $rdvs[] = [
                'id'=>$event->getId(),
                'start'=>$event->getStart()->format('Y-m-d H:i:s'),
                'end'=>$event->getEnd()->format('Y-m-d H:i:s'),
                'title'=>$event->getTitle(),
                'description'=>$event->getDescription(),
                'backgroundColor'=>$event->getBackgroundColor(),
                'textColor'=>$event->getTextColor(),
                'allDay'=>$event->getAllDay(),
            ];
        }
        $data = json_encode($rdvs);
        return $this->render('calendar/index.html.twig', [
            'data' => $data,
            'evs'=>$ev
        ]);
    }


    /**
     * @Route("/new", name="calendar_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $event = new Calendar();

        if ($request->isXmlHttpRequest()) {
            $entityManager = $this->getDoctrine()->getManager();

            $event->setTitle($request->get('title'));
            $event->setDescription($request->get('description'));
            try {
                $event->setStart(new DateTime($request->get('start')));

            $event->setEnd(new DateTime($request->get('end')));
            } catch (\Exception $e) {
            }
            $event->setBackgroundColor($request->get('backgroundColor'));
            $event->setBorderColor($request->get('borderColor'));
            $event->setTextColor($request->get('textColor'));
            $event->setAllDay($request->get('allDay'));
            $entityManager->persist($event);
            $entityManager->flush();

            return  new JsonResponse($event);

        }



    }


    /**
     * @Route("/{id}", name="calendar_show", methods={"GET"})
     */
    public function show(Calendar $calendar): Response
    {
        return $this->render('calendar/show.html.twig', [
            'calendar' => $calendar,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="calendar_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Calendar $calendar): Response
    {
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('calendar_index');
        }

        return $this->render('calendar/edit.html.twig', [
            'calendar' => $calendar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="calendar_delete", methods={"POST"})
     */
    public function delete(Request $request, Calendar $calendar): Response
    {
        if ($this->isCsrfTokenValid('delete'.$calendar->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($calendar);
            $entityManager->flush();
        }

        return $this->redirectToRoute('calendar_index');
    }
}
