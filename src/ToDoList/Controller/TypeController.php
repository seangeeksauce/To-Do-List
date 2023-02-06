<?php
namespace ToDoList\Controller;

	use \DateTime;

	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
	use ToDoList\Entity\Type;
	use Symfony\Component\HttpFoundation\Response;

	class TypeController extends Controller {
		/**
		 * @Template("ToDoList:Type:index.html.twig")
		 */
		public function indexAction(Request $request, $_route) {

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
		 * @Template("ToDoList:Type:create.html.twig")
		 */
		public function createAction(Request $request, $_route) {
			return array(
			);
		}

		/**
		 * @Template("ToDoList:Type:edit.html.twig")
		 */
		public function editAction(Request $request, $_route, $id) {
			$qb = $this->getDoctrine()->getManager()->createQueryBuilder();
				$qb
					->select('e')
					->from('ToDoList:Type', 'e')
					->where('e.id = :type_id');

				$type = $qb
					->setParameter('type_id', $id)
					->getQuery()
						->getOneOrNullResult();

			return array(
				'type' => $type,
			);
		}

		public function saveAction(Request $request, $_route) {
			$em = $this->getDoctrine()->getManager();

			$type = new Type();
			$actions = array();
			if ($request->get('actions'))
				$actions = explode(',', $request->get('actions'));

			$type
				->setName($request->get('name'))
				->setActions($actions);

				$em->persist($type);
	            $em->flush();

			return $this->redirect($this->generateUrl('type', array('id' => $type->getId())));

		}

		public function updateAction(Request $request, $_route, $id) {
			$em = $this->getDoctrine()->getManager();

			$qb = $this->getDoctrine()->getManager()->createQueryBuilder();
				$qb
					->select('e')
					->from('ToDoList:Type', 'e')
					->where('e.id = :type_id');

			$type = $qb
				->setParameter('type_id', $id)
				->getQuery()
					->getOneOrNullResult();

			$actions = array();

			if ($type) {
				if ($request->get('actions'))
					$actions = explode(',', $request->get('actions'));

				$type
					->setName($request->get('name'))
					->setActions($actions);

	            $em->flush($type);
			}
		
			return $this->redirect($this->generateUrl('type.edit', array('id' => $type->getId())));
		}

	}