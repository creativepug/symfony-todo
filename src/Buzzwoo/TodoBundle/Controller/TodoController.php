<?php

namespace Buzzwoo\TodoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Buzzwoo\TodoBundle\Entity\Task;

class TodoController extends Controller
{
	public function indexAction()
	{
		$repository = $this->getDoctrine()->getRepository('BuzzwooTodoBundle:Task');
		$unfinishedTasks = $repository->findByCompleted(0);
		$finishedTasks = $repository->findByCompleted(1);

		return $this->render('BuzzwooTodoBundle:Todo:index.html.twig',
			['unfinished' => $unfinishedTasks,
			'finished' => $finishedTasks]);
	}

	public function createAction(Request $request)
	{
		$task = new Task();
		$task->setCompleted(0);
		$form = $this->createFormBuilder($task)
			->add('name', 'text')
			->add('save', 'submit')
			->getForm();

		$form->handleRequest($request);

		if ($form->isValid())
		{
			// save new todo item into the database
			$em = $this->getDoctrine()->getManager();
			$em->persist($task);
			$em->flush();

			return $this->redirect($this->generateUrl('home'));
		}

		return $this->render(
			'BuzzwooTodoBundle:Todo:create.html.twig',
			['form' => $form->createView()
		]);
	}
}
