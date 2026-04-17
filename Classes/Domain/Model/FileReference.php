<?php

namespace T3element\Insights\Domain\Model;

class FileReference extends \TYPO3\CMS\Extbase\Domain\Model\FileReference
{
    public const VIEW_DETAIL_ONLY = 1;
    public const VIEW_LIST_ONLY = 2;
    public const VIEW_LIST_AND_DETAIL = 3;
    protected $insightview = 0;

    /**
     * @param int $insightview
     */
    public function setInsightview($insightview): void
    {
        $this->insightview = $insightview;
    }

    public function getInsightview(): int
    {
        return $this->insightview;
    }

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var string
     */
    protected $alternative = '';

    /**
     * @var string
     */
    protected $link = '';
 

    /**
     * Set File uid
     *
     * @param int $fileUid
     */
    public function setFileUid($fileUid): void
    {
        $this->uidLocal = $fileUid;
    }

    /**
     * Get File UID
     *
     * @return int
     */
    public function getFileUid(): int
    {
        return $this->uidLocal;
    }

    /**
     * Set alternative
     *
     * @param string $alternative
     */
    public function setAlternative($alternative): void
    {
        $this->alternative = $alternative;
    }

    /**
     * Get alternative
     *
     * @return string
     */
    public function getAlternative(): string
    {
        return (string) ($this->alternative !== '' ? $this->alternative : $this->getOriginalResource()->getAlternative());
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return (string) ($this->description !== '' ? $this->description : $this->getOriginalResource()->getDescription());
    }

    /**
     * Set link
     *
     * @param string $link
     */
    public function setLink($link): void
    {
        $this->link = $link;
    }

    /**
     * Get link
     *
     * @return mixed
     */
    public function getLink()
    {
        return (string) ($this->link !== '' ? $this->link : $this->getOriginalResource()->getLink());
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return (string) ($this->title !== '' ? $this->title : $this->getOriginalResource()->getTitle());
    }
 
    public function setOriginalResource(\TYPO3\CMS\Core\Resource\FileReference $originalResource): void
    {
        $this->originalResource = $originalResource;
        $this->uidLocal = (int) $originalResource->getProperty('uid');
    }

}
