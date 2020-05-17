<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Form\EventType;

/**
 * Event controller.
 * @Route("/api", name="api_")
 */
class EventController extends FOSRestController
{
    /**
     * Lists all Events.
     * @Rest\Get("/events")
     *
     * @return Response
     */
    public function getEvents()
    {
        $repository = $this->getDoctrine()->getRepository(Event::class);
        $events = $repository->findall();
        return $this->handleView($this->view($events));
    }
    /**
     * Create Event.
     * @Rest\Post("/event")
     *
     * @return Response
     */
    public function postEvent(Request $request)
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
        }
        return $this->handleView($this->view($form->getErrors()));
    }

    /**
     * Create Event.
     * @Rest\Delete("/event/{id}")
     *
     * @return Response
     */
    public function deleteEvent(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Event::class);
        $event = $repository->find($request->get('id'));
        if ($event) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($event);
            $em->flush();
        }
    }
}
