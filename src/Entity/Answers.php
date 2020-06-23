<?php

namespace App\Entity;

use App\Repository\AnswersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnswersRepository::class)
 */
class Answers
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $answerText;

    /**
     * @ORM\Column(type="datetime")
     */
    private $answeredOn;

    /**
     * @ORM\ManyToOne(targetEntity=Questions::class, inversedBy="answers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="answers", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $votes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnswerText(): ?string
    {
        return $this->answerText;
    }

    public function setAnswerText(string $answerText): self
    {
        $this->answerText = $answerText;

        return $this;
    }

    public function getAnsweredOn(): ?\DateTimeInterface
    {
        return $this->answeredOn;
    }

    public function setAnsweredOn(\DateTimeInterface $answeredOn): self
    {
        $this->answeredOn = $answeredOn;

        return $this;
    }

    public function getQuestion(): ?questions
    {
        return $this->question;
    }

    public function setQuestion(?questions $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAuthor(): ?Users
    {
        return $this->author;
    }

    public function setAuthor(?Users $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getVotes(): ?int
    {
        return $this->votes;
    }

    public function setVotes(int $votes): self
    {
        $this->votes = $votes;

        return $this;
    }
}
