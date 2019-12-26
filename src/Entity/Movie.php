<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use DateTime;

/**
 * Class Movie
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 * @ORM\Table(name="movie")
 */
class Movie extends EntityAbstract
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
     * @ORM\Column(name="title", type="string", length=150, nullable=false)
     * @var string $title
     */
    private $title;

    /**
     * @ORM\Column(name="poster", type="string", length=255, nullable=false)
     * @var string $poster
     */
    private $poster;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="movies")
     */
    private $users;

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
     * Movie constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->dateInsert = new DateTime();
        $this->dateUpdate = new DateTime();
        $this->users = new ArrayCollection();
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getPoster(): string
    {
        return $this->poster;
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
     * @param string $title
     * @return Movie
     */
    public function setTitle(string $title): Movie
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $poster
     * @return Movie
     */
    public function setPoster(string $poster): Movie
    {
        $this->poster = $poster;
        return $this;
    }
    #endregion

    #region Collection
    public function addUser(User $user): self
    {
        $this->users[] = $user;

        return $this;
    }

    public function removeUser(User $user): bool
    {
        return $this->users->removeElement($user);
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }
    #endregion
}
