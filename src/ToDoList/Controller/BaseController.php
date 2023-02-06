<?php

namespace ToDoList\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ToDoList\Entity\Event;

class BaseController extends Controller {

	/**
	 * @Template("ToDoList:Admin:index.html.twig")
	 */
	public function indexAction(Request $request, $_route) {

		$qb = $this->getDoctrine()->getManager()->createQueryBuilder();
			$qb
				->select('e')
				->from('ToDoList:Event', 'e')
				->where('e.user = :user_id');

			$events = $qb
				->setParameter('user_id','1')
				->getQuery()
					->getResult();

		return array(
			'events' => $events,
		);
	}

}
