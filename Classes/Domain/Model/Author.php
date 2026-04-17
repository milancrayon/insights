<?php
declare(strict_types=1);

namespace T3element\Insights\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;

class Author extends AbstractEntity
{
    /**
     * name
     *
     * @var string
     */
    protected $name = '';

    /**
     * email
     *
     * @var string
     */
    protected $email = '';

    /**
     * avtar
     *
     * @Lazy
     * @var ObjectStorage<FileReference>
     */
    protected $avtar;

    /**
     * slug
     *
     * @var string
     */
    protected $slug = '';


    /**
     * designation
     *
     * @var string
     */
    protected $designation = '';

    /**
     * intro
     *
     * @var string
     */
    protected $intro = '';

    /**
     * socialmedia
     *
     * @Lazy
     * @var ObjectStorage<Socialmedia>
     */
    protected $socialmedia;


    public function __construct()
    {
        $this->initializeObject();
    }

    public function initializeObject(): void
    {
        $this->avtar = new ObjectStorage();
        $this->socialmedia = new ObjectStorage();
    }
    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param string $email email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function addAvtar(FileReference $avtar): void
    {
        $this->avtar = $this->getAvtar();
        $this->avtar->attach($avtar);
    }
    /**
     * @return ObjectStorage<FileReference>
     */
    public function getAvtar(): ObjectStorage
    {
        return $this->avtar;
    }
    public function removeAvtar(FileReference $avtar): void
    {
        $this->avtar = $this->getAvtar();
        $this->avtar->detach($avtar);
    }
    /**
     * @param ObjectStorage<FileReference> $avtar
     */
    public function setAvtar(ObjectStorage $avtar): void
    {
        $this->avtar = $avtar;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Set slug
     *
     * @param string $slug slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }


    /**
     * Get designation
     *
     * @return string
     */
    public function getDesignation(): string
    {
        return $this->designation;
    }

    /**
     * Set designation
     *
     * @param string $designation designation
     */
    public function setDesignation($designation): void
    {
        $this->designation = $designation;
    }

    /**
     * Get intro
     *
     * @return string
     */
    public function getIntro(): string
    {
        return $this->intro;
    }

    /**
     * Set intro
     *
     * @param string $intro intro
     */
    public function setIntro($intro): void
    {
        $this->intro = $intro;
    }

    public function addSocialmedia(Socialmedia $socialmedia): void
    {
        $this->socialmedia = $this->getSocialmedia();
        $this->socialmedia->attach($socialmedia);
    }
    /**
     * @return ObjectStorage<Socialmedia>
     */
    public function getSocialmedia(): ObjectStorage
    {
        return $this->socialmedia;
    }
    public function removeSocialmedia(Socialmedia $socialmedia): void
    {
        $this->socialmedia = $this->getSocialmedia();
        $this->socialmedia->detach($socialmedia);
    }
    /**
     * @param ObjectStorage<Socialmedia> $socialmedia
     */
    public function setSocialmedia(ObjectStorage $socialmedia): void
    {
        $this->socialmedia = $socialmedia;
    }
}
