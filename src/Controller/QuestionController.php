<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;

class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage()
    {
        $questions=$this->getDoctrine()
            ->getRepository('App:Questions')
            ->findAll();

//        dd($questions);

        return $this->render('question/homepage.html.twig',['questions'=>$questions]);
    }

    /**
     * @Route("/questions/{slug}")
     */
    public function show($slug)
    {
        $answers=$this->getDoctrine()
            ->getRepository('App:Answers')
            ->findBy(['question' => $slug]);
//            ->find($slug);

//        $answers = [
//            'Make sure your cat is sitting purrrfectly still 🤣',
//            'Honestly, I like furry shoes better than MY cat',
//            'Maybe... try saying the spell backwards?',
//        ];

//        $question=$answers[1]->getQuestion(); #->getQuestionText();

        //        dd($answers);
//                dd($question);

        return $this->render('question/show.html.twig', [
//            'question' => $answers[1]->getQuestion(),
            'question' => ucwords(str_replace('-', ' ', $slug)),
            'answers' => $answers,
        ]);
    }
}
