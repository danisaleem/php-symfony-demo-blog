<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
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
        if ($request->isMethod('post'))
        {
            $current_user = $this->getUser();

            $question=new Question();
            $question->setAuthor($current_user);
            $question->setCreatedOn(\DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s")));

            $question->setQuestionText($request->get('txtQuestion'));
            $question->setQuestionTitle($request->get('txtQuestionTitle'));
            $question->setVotes(0); // initialize votes to 0

            $em=$this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush($question);

            return $this->redirectToRoute('app_homepage');
        }

        $questions=$this->getDoctrine()
            ->getRepository('App:Question')
            ->findAll();

        return $this->render('question/homepage.html.twig',['questions'=>$questions]);
    }

    /**
     * @Route("/questions/{slug}" , name="app_question_show", methods={"GET","POST"})
     */
    public function show(Request $request, $slug)
    {
        $question=$this->getDoctrine()
            ->getRepository('App:Question')
            ->find($slug);

        if ($request->isMethod('post'))
        {
            $current_user = $this->getUser();

            $answer=new Answer();
            $answer->setAuthor($current_user);
            $answer->setAnsweredOn(\DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s")));
            $answer->setAnswerText($request->get('txtAnswer'));
            $answer->setQuestion($question);
            $answer->setVotes(0); // initialize votes to 0

            $em=$this->getDoctrine()->getManager();
            $em->persist($answer);
            $em->flush($question);
            $em->flush($answer);

            return $this->redirectToRoute('app_question_show',['slug'=>$slug]);
        }
        
        $answers=$this->getDoctrine()
            ->getRepository('App:Answer')
            ->findBy(['question' => $slug]);

        return $this->render('question/show.html.twig', [
            'question' => $question,
            'answers' => $answers,
        ]);
    }
}
