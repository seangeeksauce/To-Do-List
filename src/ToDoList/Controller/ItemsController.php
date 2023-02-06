<?php

	namespace ToDoList\Controller;

	use \DateTime;
	use \BadMethodCallException;
	use \Exception;

	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
	use ToDoList\Entity\Event;
	use Symfony\Component\HttpFoundation\Response;

	class ItemsController extends Controller {
		const KEY = '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';

		/**
		 * @Template("ToDoList:Items:index.html.twig")
		 */
		public function indexAction(Request $request, $_route) {

			$qb = $this->getDoctrine()->getManager()->createQueryBuilder();
				$qb
					->select('e')
					->from('ToDoList:Event', 'e')
					->where('e.user = :user_id')
					->andWhere('e.completedOn is null')
					->addOrderBy('e.sortOrder', 'ASC');

				$events = $qb
					->setParameter('user_id','1')
					->getQuery()
						->getResult();

			return array(
				'events' => $events,
			);
		}

		/**
		 * @Template("ToDoList:Items:create.html.twig")
		 */
		public function createAction(Request $request, $_route) {

			$qb = $this->getDoctrine()->getManager()->createQueryBuilder();
			$qb
				->select('t')
				->from('ToDoList:Type', 't');

			$types = $qb
				->getQuery()
					->getResult();

			return array(
				'types' => $types,
			);
		}

		/**
		 * @Template("ToDoList:Items:edit.html.twig")
		 */
		public function editAction(Request $request, $_route, $id) {
			$qb = $this->getDoctrine()->getManager()->createQueryBuilder();
				$qb
					->select('e')
					->from('ToDoList:Event', 'e')
					->where('e.user = :user_id')
					->andWhere('e.id = :event_id');

				$event = $qb
					->setParameter('user_id','1')
					->setParameter('event_id', $id)
					->getQuery()
						->getOneOrNullResult();

				$qb = $this->getDoctrine()->getManager()->createQueryBuilder();
				$qb
					->select('t')
					->from('ToDoList:Type', 't');

				$types = $qb
					->getQuery()
						->getResult();

			return array(
				'event' => $event,
				'types' => $types,
			);
		}

		public function sendEmailAction($key = null) {
			if ($key !== self::KEY)
				throw new Exception('Missing Identification Key!!!!');

	        $mailer = $this->get('send_notification', $this->getDoctrine());
	        $mailer->send();

	        $response = array(
				"code" => 100, 
				"success" => true,
			);

			return new Response(json_encode($response)); 
    	}


		public function saveAction(Request $request, $_route) {
			$em = $this->getDoctrine()->getManager();
			
			$event = new Event();

			$event
				->setTitle($request->get('title'))
				->setDescription($request->get('description'))
				->setUser('1')
				->setCreatedDate(new DateTime('now'))
				->setCompletionDate(new DateTime($request->get('completionDate')));

				if ($request->get('type') !== null && is_numeric($request->get('type'))) {
					$type = $this->getDoctrine()->getRepository('ToDoList:Type')->find((int)$request->get('type'));
					$event->setType($type->getId());
				} else
					$event->setType(null);

				$em->persist($event);
	            $em->flush();

			return $this->redirect($this->generateUrl('items', array('id' => $event->getId())));

		}

		public function updateAction(Request $request, $_route, $id) {
			$em = $this->getDoctrine()->getManager();

			$qb = $this->getDoctrine()->getManager()->createQueryBuilder();
				$qb
					->select('e')
					->from('ToDoList:Event', 'e')
					->where('e.user = :user_id')
					->andWhere('e.id = :event_id');

			$event = $qb
				->setParameter('user_id','1')
				->setParameter('event_id', $id)
				->getQuery()
					->getOneOrNullResult();

			if ($event) {
				$event
					->setTitle($request->get('title'))
					->setDescription($request->get('description'))
					->setUser('1')
					->setCreatedDate(new DateTime())
					->setCompletionDate(new DateTime($request->get('completionDate')));

				if ($request->get('type') !== null && is_numeric($request->get('type'))) {
					$type = $this->getDoctrine()->getRepository('ToDoList:Type')->find((int)$request->get('type'));
					$event->setType($type->getId());
				} else
					$event->setType(null);

				if ($request->get('complete') !== null )
					$event->setCompletedOn(new DateTime());

				$em->flush($event);
			}
		
			return $this->redirect($this->generateUrl('items.edit', array('id' => $event->getId())));
		}

		public function ajaxUpdateAction(Request $request){
			// $request = $this->container->get('request');
			$em = $this->getDoctrine()->getManager();
			$payload = json_decode($request->getContent());

			foreach ($payload->fields as $k => $v) {
			
				$qb = $this->getDoctrine()->getManager()->createQueryBuilder();
				$qb
					->select('e')
					->from('ToDoList:Event', 'e')
					->where('e.id = :id');

				$event = $qb
					->setParameter('id', (int)$v->id )
					->getQuery()
						->getOneOrNullResult();

				$event->setSortOrder((int)$v->order);
				$em->flush($event);
			}

			$response = array(
				"code" => 100, 
				"success" => true,
			);

			return new Response(json_encode($response)); 
		}      

	}
