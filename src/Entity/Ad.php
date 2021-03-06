<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AdRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"title"},
 *     message= "Ce titre est déjà utilisé... Veuillez modifier votre titre!"
 * )
 */
class Ad
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(
     *      min = 5,
     *      max = 100,
     *      minMessage = "Vous devez écrire un texte d'au moins {{ limit }} caractères",
     *      maxMessage = "Vous devez écrire un texte de {{ limit }} caractères maximum"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="float")
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 5,
     *      max = 200,
     *      minMessage = "Vous devez écrire un texte d'au moins {{ limit }} caractères",
     *      maxMessage = "Vous devez écrire un texte de {{ limit }} caractères maximum"
     * )
     * @Assert\Type(type="string")
     * @ORM\Column(type="text")
     */
    private $introduction;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 50,
     *      max = 2000,
     *      minMessage = "Vous devez écrire un texte d'au moins {{ limit }} caractères",
     *      maxMessage = "Vous devez écrire un texte de {{ limit }} caractères maximum"
     * )
     * @ORM\Column(type="text")
     */
    private $contents;

    /**
     * @Assert\NotBlank()
     * @Assert\Url(
     *      message = "Ceci n'est pas une url valide"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $coverImage;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     * @ORM\Column(type="integer")
     */
    private $rooms;

    /**
     * @Assert\NotBlank()
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="ad", orphanRemoval=true)
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="ads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="ad")
     */
    private $bookings;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    /**
     * Sert a initialiser un slug a chaque création d'annonce
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function initializeSlug(){
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
    }

    /**
     * Permet d'obtenir un tableau des jours déjà loué
     *
     * @return array Un array Datetime qui represente les jours déja loué
     */
    public function getNotAvailableDays(){
        $notAvailableDays = [];

        //Pour rappel un timestamp est le tps écoulé depuis 19xx jusqu'à la date choisi

        foreach($this->bookings as $booking){ //je prends chaque date booked
           // je calcule le timestamp entre la startDate et la endDate
            $resultat = range(
               $booking->getStartDate()->getTimestamp(),
               $booking->getEndDate()->getTimestamp(),
               24 * 60 * 60 // caclcule du nbre de jour en timestamp entre les deux
           );

            // permet de transformer un tableau en un tableau transformé, ici on passe de timestamp en datetime
          $days = array_map(function ($dayTimestamp){
              return new \DateTime(date('Y-m-d', $dayTimestamp));
              }, $resultat);

          $notAvailableDays = array_merge($notAvailableDays, $days);
        }

        return $notAvailableDays;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getcontents(): ?string
    {
        return $this->contents;
    }

    public function setcontents(string $contents): self
    {
        $this->contents = $contents;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(string $coverImage): self
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAd($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getAd() === $this) {
                $image->setAd(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setAd($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getAd() === $this) {
                $booking->setAd(null);
            }
        }

        return $this;
    }
}
