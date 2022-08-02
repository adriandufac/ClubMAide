<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 */
class Participant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $prenom;

    /**
     * @ORM\Column(type="integer", length=10)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $motPasse;

    /**
     * @ORM\Column(type="boolean")
     */
    private $administrateur;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="participants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @ORM\ManyToMany(targetEntity=Sortie::class, inversedBy="participantsInscrits")
     */
    private $inscriptionsSorties;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="participantsOrganisateurs")
     */
    private $organisationSorties;

    public function __construct()
    {
        $this->inscriptionsSorties = new ArrayCollection();
        $this->organisationSorties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getMotPasse(): ?string
    {
        return $this->motPasse;
    }

    public function setMotPasse(string $motPasse): self
    {
        $this->motPasse = $motPasse;

        return $this;
    }

    public function isAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(bool $administrateur): self
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getInscriptionsSorties(): Collection
    {
        return $this->inscriptionsSorties;
    }

    public function addInscriptionsSorty(Sortie $inscriptionsSorty): self
    {
        if (!$this->inscriptionsSorties->contains($inscriptionsSorty)) {
            $this->inscriptionsSorties[] = $inscriptionsSorty;
        }

        return $this;
    }

    public function removeInscriptionsSorty(Sortie $inscriptionsSorty): self
    {
        $this->inscriptionsSorties->removeElement($inscriptionsSorty);

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getOrganisationSorties(): Collection
    {
        return $this->organisationSorties;
    }

    public function addOrganisationSorty(Sortie $organisationSorty): self
    {
        if (!$this->organisationSorties->contains($organisationSorty)) {
            $this->organisationSorties[] = $organisationSorty;
            $organisationSorty->setParticipantsOrganisateurs($this);
        }

        return $this;
    }

    public function removeOrganisationSorty(Sortie $organisationSorty): self
    {
        if ($this->organisationSorties->removeElement($organisationSorty)) {
            // set the owning side to null (unless already changed)
            if ($organisationSorty->getParticipantsOrganisateurs() === $this) {
                $organisationSorty->setParticipantsOrganisateurs(null);
            }
        }

        return $this;
    }
}
