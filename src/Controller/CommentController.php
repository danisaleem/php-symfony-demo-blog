<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comments/{id}/vote/{direction<up|down>}", methods="POST")
     */
    public function commentVote($id, $direction, LoggerInterface $logger)
    {
        // todo - use id to query the database

        $answer=$this->getDoctrine()
            ->getRepository('App:Answers')
            ->findOneBy(['id' => $id]);

        $votes = $answer->getVotes();

        // todo - use id to query the database


        // use real logic here to save this to the database
        if ($direction === 'up') {
            $logger->info('voting up');

            $votes++; // up vote
            $currentVoteCount = $votes;
//            $currentVoteCount = rand(7, 100);
        } else {
            $votes--; // down vote
            $logger->info('voting down');
            $currentVoteCount = $votes;
//            $currentVoteCount = rand(0, 5);
        }

        $answer->setVotes($currentVoteCount); // update new vote count

        $em=$this->getDoctrine()->getManager();
        $em->persist($answer);
        $em->flush($answer);

        return $this->json(['votes' => $currentVoteCount]);
    }
}
