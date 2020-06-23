<?php

namespace App\Controller;

use App\Entity\Answers;
use App\Entity\Questions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session;

class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage", methods={"GET","POST"})
     */
    public function homepage(Request $request)
    {
        $this->get('session')->set("userId",1); # user management

        if ($request->isMethod('post'))
        {
            // getting current user id from session
            $userId=$this->get('session')->get('userId', []);

            $current_user=$this->getDoctrine()
            ->getRepository('App:Users')
            ->findOneBy(['id' => $userId]);

            $question=new Questions();
            $question->setAuthor($current_user);
            $question->setCreatedOn(\DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s")));

            $question->setQuestionText($request->get('txtQuestion'));
            $question->setQuestionTitle($request->get('txtQuestionTitle'));

            $em=$this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush($question);

            return $this->redirectToRoute('app_homepage');
        }

        $questions=$this->getDoctrine()
            ->getRepository('App:Questions')
            ->findAll();

        return $this->render('question/homepage.html.twig',['questions'=>$questions]);
    }

    /**
     * @Route("/questions/{slug}" , name="app_question_show", methods={"GET","POST"})
     */
    public function show(Request $request, $slug)
    {
        $question=$this->getDoctrine()
            ->getRepository('App:Questions')
            ->find($slug);

        if ($request->isMethod('post'))
        {
            // getting current user id from session for author
            $userId=$this->get('session')->get('userId', []);

            $current_user=$this->getDoctrine()
                ->getRepository('App:Users')
                ->findOneBy(['id' => $userId]);

            $answer=new Answers();
            $answer->setAuthor($current_user);
            $answer->setAnsweredOn(\DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s")));
            $answer->setAnswerText($request->get('txtAnswer'));
            $answer->setQuestion($question);

            $em=$this->getDoctrine()->getManager();
            $em->persist($answer);
            $em->flush($question);
            $em->flush($answer);

            return $this->redirectToRoute('app_question_show',['slug'=>$slug]);
        }
        
        $answers=$this->getDoctrine()
            ->getRepository('App:Answers')
            ->findBy(['question' => $slug]);

        return $this->render('question/show.html.twig', [
            'question' => $question,
            'answers' => $answers,
        ]);
    }
}
