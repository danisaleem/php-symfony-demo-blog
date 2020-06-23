<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{
    /**
     * @Route("/comments/{id}/vote/{direction<up|down>}", methods="POST")
     */
    public function AnswerVote($id, $direction, LoggerInterface $logger)
    {
        $answer=$this->getDoctrine()
            ->getRepository('App:Answers')
            ->findOneBy(['id' => $id]);

        $votes = $answer->getVotes();

        if ($direction === 'up') {
            $logger->info('voting up from ' + $votes);
            $votes++; // up vote
            $currentVoteCount = $votes;
        } else {
            $logger->info('voting down from '+ $votes);
            $votes--; // down vote
            $currentVoteCount = $votes;
        }

        $answer->setVotes($currentVoteCount); // update new vote count
        $em=$this->getDoctrine()->getManager();
        $em->persist($answer);
        $em->flush($answer);

        return $this->json(['votes' => $currentVoteCount]);
    }
}