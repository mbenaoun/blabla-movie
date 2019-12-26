<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use DateTime;

/**
 * Class User
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="uq_key_email",columns={"email"})})
 */
class User
{
    #region Attributes
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned":true})
     * @var int $id
     */
    private $id;

    /**
     * @ORM\Column(name="pseudo", type="string", length=50, nullable=false)
     * @var string $pseudo
     */
    private $pseudo;

    /**
     * @ORM\Column(name="email", type="string", length=255, unique=true, nullable=false)
     * @var string $email
     */
    private $email;

    /**
     * @ORM\Column(name="date_of_birth", type="date", nullable=false)
     * @var DateTime $dateOfBirth
     */
    private $dateOfBirth;

    /**
     * @ORM\ManyToMany(targetEntity="Movie", inversedBy="users", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="users_movies",
     *     joinColumns={
     *          @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="movie_id", referencedColumnName="id")
     *     }
     * )
     */
    private $movies;

    /**
     * @ORM\Column(name="date_insert", type="datetime", nullable=false)
     * @var DateTime $dateInsert
     */
    private $dateInsert;

    /**
     * @ORM\Column(name="date_update", type="datetime", nullable=false)
     * @var DateTime $dateUpdate
     */
    private $dateUpdate;
    #endregion

    #region Constructor
    /**
     * User constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->dateInsert = new DateTime();
        $this->dateUpdate = new DateTime();
        $this->movies = new ArrayCollection();
    }
    #endregion

    #region Getters
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return DateTime
     */
    public function getDateOfBirth(): DateTime
    {
        return $this->dateOfBirth;
    }

    /**
     * @return DateTime
     */
    public function getDateInsert(): DateTime
    {
        return $this->dateInsert;
    }

    /**
     * @return DateTime
     */
    public function getDateUpdate(): DateTime
    {
        return $this->dateUpdate;
    }
    #endregion

    #region Setters
    /**
     * @param string $pseudo
     * @return User
     */
    public function setPseudo(string $pseudo): User
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param DateTime $dateOfBirth
     * @return User
     */
    public function setDateOfBirth(DateTime $dateOfBirth): User
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }
    #endregion

    #region Collection
    public function addMovie(Movie $movie): self
    {
        $this->movies[] = $movie;

        return $this;
    }

    public function removeMovie(Movie $movie): bool
    {
        return $this->movies->removeElement($movie);
    }

    public function getMovies(): Collection
    {
        return $this->movies;
    }

    /**
     * @param ArrayCollection $movies
     * @return User
     */
    public function setMovies(ArrayCollection $movies): User
    {
        $this->movies = $movies;
        return $this;
    }

    /**
     * @param Movie|null $movie
     * @return bool
     */
    public function attachMovie(?Movie $movie): bool
    {
        $isAttached = false;
        if($this->getMovies()->count() < 3 && $movie instanceof Movie) {
            $this->addMovie($movie);
            $isAttached = true;
        }

        return $isAttached;
    }
    #endregion
}
