<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ad", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="Attention ceci doit être une date au format jj/mm/aaaa !")
     * @Assert\GreaterThan("today", message="La date d'arrivée ne peut être ultérieur à aujourd'hui")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\Date(message="Attention ceci doit être une date au format jj/mm/aaaa !")
     * @Assert\GreaterThan(propertyPath="startDate", message="La date de départ ne peut être avant/ou le même jour que votre arrivée !")
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=255, nullable=true))
     */
    private $message;

    /**
     * Set the createdAt to today and the amount is calculated with the diff between startDate and endDate
     *
     * @ORM\PrePersist()
     */
    public function prePersist(){
        if(empty($this->createdAt)){
            $this->createdAt = new \DateTime();
        }
        if(empty($this->amount)){
            $this->amount = $this->ad->getPrice() * $this->getDuration();
        }
    }

    /**
     * Permet de connaitre le temps du séjour en faisant une diff entre startDate et endDate
     *
     * @return integer
     */
    public function getDuration(){
        $diff = $this->endDate->diff($this->startDate);
        return $diff->days;
    }


    /**
     * Permet d'obtenir un tableau des jours déjà loué
     *
     * @return array Un array Datetime qui represente les jours déja loué
     */
    public function getNotAvailableDays(){
        $notAvailableDays = [];

        //Pour rappel un timestamp est le tps écoulé depuis le 1er janvier 1970 00:00:00 GMT jusqu'à la date choisi mesurée en secondes

        foreach($this->bookings as $booking){ //je prends chaque date booked
            // je calcule le timestamp entre la startDate et la endDate
            $result = range(
                $booking->getStartDate()->getTimestamp(),
                $booking->getEndDate()->getTimestamp(),
                24 * 60 * 60 // caclcul du nbre de jour en timestamp entre les deux
            );

            // permet de transformer un tableau en un tableau transformé, ici on passe de timestamp en datetime
            $days = array_map(
                function ($dayTimestamp){
                    return new DateTime(date('Y-m-d', $dayTimestamp));
                }, $result);

            $notAvailableDays = array_merge($notAvailableDays, $days);
        }

        return $notAvailableDays;
    }


    /**
     * Permet de savoir quelles sont les dates dispo
     *
     * @return mixed
     */
    public function isBookableDates(){
        // On récupère les dates non dispo
        $notAvailableDays   = $this->ad->getNotAvailableDays();

        // Comparaison entre les dates choisies et les dates prises
        $bookingDays        = $this->getDays();

        // Je transforme mes 2 tableaux qui sont des datetime en string car plus facile a comparer
        $days = array_map(function($day){
           return $day->format('Y-m-d');
        }, $bookingDays);

        $notAvailable = array_map(function ($day){
            return $day->format('Y-m-d');
        },$notAvailableDays);

        //on peut refacto le code du dessus
//        $formatDay = function($day){
//            return $day->format('Y-m-d');
//        };

//        $bookedDays     = array_map($formatDay, $bookingDays);
//
//        $notAvailable   = array_map($formatDay, $notAvailableDays);

        // Je cherche dans mon tableau notAvailable si je trouve une de mes valeurs de mon tableau bookedDays
        foreach ($days as $day){
            if(array_search($day, $notAvailable) !== false) return false;

        }

        return true;
    }


    /**
     * Permet de connaitre quelles sont les jours choisis par l'utilisateur
     *
     * @return array
     */
    public function getDays(){
        $resultat = range(
            $this->startDate->getTimestamp(),
            $this->endDate->getTimestamp(),
            24 * 60 * 60
        );

        $days = array_map(function ($dayTimestamp){
                return new \DateTime(date('Y-m-d', $dayTimestamp));
                }, $resultat );

        return $days;

    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

}
