<?php
	namespace ToDoList\Service;

	use \BadMethodCallException;
	use \Exception;
	use \DateTime;

	use Doctrine\Bundle\DoctrineBundle\Registry;
	use \SendGrid;

	class EmailService {

		private $doctrine;
		const KEY = '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';

		public function __construct($key, Registry $doctrine) {
			$this->doctrine = $doctrine;
			if ($key !== self::KEY)
				throw new Exception('Missing Identification Key!!!!');
		}

		public function send() {
			$sendgrid = new SendGrid(self::KEY);

			$email = new SendGrid\Email();
			$email
				->addTo('sean@crazygeekdesign.com')
				->setFrom('todolist@crazygeekdesign.com')
				->setSubject('You have items that need to be completed soon!');

			$qb = $this->doctrine->getManager()->createQueryBuilder();
				$qb
					->select('e')
					->from('ToDoList:Event', 'e')
					->where('e.user = :user_id')
					->andWhere('e.completedOn is null');

			$events = $qb
				->setParameter('user_id','1')
				->getQuery()
					->getResult();

			if ($events) {
				$html = '';
				foreach ($events as $event) {
					$today = new DateTime('today');
					$date = '';
					foreach ($event->getCompletionDate() as $key => $value)
						if ($key === 'date')
							$date = $value;
				
					if ($event->getCompletionDate() >= $today) {
						$date = new DateTime($date);
						$date = $date->format('m/d/Y');
						$result = $date;

						$html .= sprintf('<strong>Event Title:</strong> %s is due on <strong>%s</strong>! <br><br>',$event->getTitle(), $result);
						$email->setHtml($html);
					}
				}
				$sendgrid->send($email);
			}
		}
	}