<?php
declare(strict_types=1);

namespace T3element\Insights\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;

class Socialmedia extends AbstractEntity
{
    /**
     * f17654463090
     *
     * @var string
     */
    protected $f17654463090 = '';

    /**
     * f17654463231
     *
     * @Lazy
     * @var ObjectStorage<FileReference>
     */
    protected $f17654463231;

    /**
     * @var Author
     */
    protected Author $parent; 
    protected Author|null $secondParent; 
        
    public function __construct()
    {
        $this->initializeObject();
    }

    public function initializeObject(): void
    {
        $this->f17654463231 = new ObjectStorage();
    } 
    /**
     * Get f17654463090
     *
     * @return string
     */
    public function getF17654463090(): string
    {
        return $this->f17654463090;
    }

    /**
     * Set f17654463090
     *
     * @param string $f17654463090 f17654463090
     */
    public function setF17654463090($f17654463090): void
    {
        $this->f17654463090 = $f17654463090;
    }
            
    public function addF17654463231(FileReference $f17654463231): void
    {
        $this->f17654463231 = $this->getF17654463231();
        $this->f17654463231->attach($f17654463231);
    } 
    /**
     * @return ObjectStorage<FileReference>
     */
    public function getF17654463231(): ObjectStorage
    {
        return $this->f17654463231;
    } 
    public function removeF17654463231(FileReference $f17654463231): void
    {
        $this->f17654463231 = $this->getF17654463231();
        $this->f17654463231->detach($f17654463231);
    } 
    /**
     * @param ObjectStorage<FileReference> $f17654463231
     */
    public function setF17654463231(ObjectStorage $f17654463231): void
    {
        $this->f17654463231 = $f17654463231;
    }        
        
}
