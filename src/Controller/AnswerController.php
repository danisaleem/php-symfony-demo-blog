<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AnswerController extends AbstractController
{
    /**
     * @Route("/answer/{id}/vote/{direction<up|down>}", name="app_answer_vote", methods="POST")
     * @param $id
     * @return JsonResponse
     */
    public function AnswerVote($id, $direction, LoggerInterface $logger)
    {
        $answer=$this->getDoctrine()
            ->getRepository('App:Answer')
            ->findOneBy(['id' => $id]);

        $votes = $answer->getVotes();

        if ($direction === 'up') {
            $logger->info('voting up from ' . strval($votes));
            $votes++; // up vote
            $currentVoteCount = $votes;
        } else {
            $logger->info('voting down from ' . strval($votes));
            $votes--; // down vote
            $currentVoteCount = $votes;
        }

        $answer->setVotes($currentVoteCount); // update new vote count
        $em=$this->getDoctrine()->getManager();
        $em->persist($answer);
        $em->flush($answer);

        return $this->json(['votes' => $currentVoteCount . ' votes']);
    }

    /**
     * @Route("/answer/delete/{id}", methods={"DELETE"}, name="delete_answer")
     * @param $id
     */
    public function delete($id){
        if ($this->isGranted('ROLE_USER')) {
            $answer=$this->getDoctrine()
                ->getRepository('App:Answer')
                ->find($id);

            $em = $this->getDoctrine()->getManager();
            $em->remove($answer);
            $em->flush();
            $response = new Response();
            $response->send();
            return $this->json(['result' => 'success']);
        }
        else
        {
            return $this->json(['result' => 'you are not allowed to delete this answer']);
        }
    }
}