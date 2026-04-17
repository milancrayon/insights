<?php
declare(strict_types=1);

namespace T3element\Insights\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Config extends AbstractEntity
{
    /**
     * socialmedia
     * 
     */
    protected $socialmedia = '';


    /** 
     *
     * @var string
     */
    protected $templates = '';

    /** 
     * @var int
     */
    protected $statusToShow = 0;

    /** 
     * @var int
     */
    protected $displaycomment = 0;

    /** 
     * @var int
     */
    protected $nextprvbtn = 0;
    /** 
     * @var int
     */
    protected $socialshare = 0;
    /** 
     * @var int
     */
    protected $addcomments = 0;
    /** 
     * @var int
     */
    protected $displayauthor = 0;
    /** 
     * @var int
     */
    protected $langwisecomment = 0;

    /** 
     * @var int
     */
    protected $storageid = 0;

    /** * @var string
     */
    protected $aioptions = '';

    /**
     * Get socialmedia
     *
     * @return string
     */
    public function getSocialmedia(): string
    {
        return $this->socialmedia;
    }

    /**
     * Set socialmedia
     *
     * @param string $socialmedia socialmedia
     */
    public function setSocialmedia($socialmedia): void
    {
        $this->socialmedia = $socialmedia;
    }

    /**
     * Get templates
     *
     * @return string
     */
    public function getTemplates(): string
    {
        return $this->templates;
    }

    /**
     * Set templates
     *
     * @param string $templates templates
     */
    public function setTemplates($templates): void
    {
        $this->templates = $templates;
    }

    /**
     * Get statusToShow
     *
     * @return int
     */
    public function getStatusToShow(): int
    {
        return (int)$this->statusToShow;
    }

    /**
     * Set statusToShow
     *
     * @param mixed $statusToShow statusToShow
     */
    public function setStatusToShow($statusToShow): void
    {
        $this->statusToShow = (int)$statusToShow;
    }
    /**
     * Get storageid
     *
     * @return int
     */
    public function getStorageid(): int
    {
        return (int)$this->storageid;
    }

    /**
     * Set storageid
     *
     * @param mixed $storageid storageid
     */
    public function setStorageid($storageid): void
    {
        $this->storageid = (int)$storageid;
    }


    /**
     * Get displaycomment
     *
     * @return int
     */
    public function getDisplaycomment(): int
    {
        return (int)$this->displaycomment;
    }

    /**
     * Set displaycomment
     *
     * @param mixed $displaycomment displaycomment
     */
    public function setDisplaycomment($displaycomment): void
    {
        $this->displaycomment = (int)$displaycomment;
    }

    /**
     * Get nextprvbtn
     *
     * @return int
     */
    public function getNextprvbtn(): int
    {
        return (int)$this->nextprvbtn;
    }

    /**
     * Set nextprvbtn
     *
     * @param mixed $nextprvbtn nextprvbtn
     */
    public function setNextprvbtn($nextprvbtn): void
    {
        $this->nextprvbtn = (int)$nextprvbtn;
    }

    /**
     * Get socialshare
     *
     * @return int
     */
    public function getSocialshare(): int
    {
        return (int)$this->socialshare;
    }

    /**
     * Set socialshare
     *
     * @param mixed $socialshare socialshare
     */
    public function setSocialshare($socialshare): void
    {
        $this->socialshare = (int)$socialshare;
    }

    /**
     * Get addcomments
     *
     * @return int
     */
    public function getAddcomments(): int
    {
        return (int)$this->addcomments;
    }

    /**
     * Set addcomments
     *
     * @param mixed $addcomments addcomments
     */
    public function setAddcomments($addcomments): void
    {
        $this->addcomments = (int)$addcomments;
    }
    /**
     * Get displayauthor
     *
     * @return int
     */
    public function getDisplayauthor(): int
    {
        return (int)$this->displayauthor;
    }

    /**
     * Set displayauthor
     *
     * @param mixed $displayauthor displayauthor
     */
    public function setDisplayauthor($displayauthor): void
    {
        $this->displayauthor = (int)$displayauthor;
    }

    /**
     * Get langwisecomment
     *
     * @return int
     */
    public function getLangwisecomment(): int
    {
        return (int)$this->langwisecomment;
    }

    /**
     * Set langwisecomment
     *
     * @param int $langwisecomment langwisecomment
     */
    public function setLangwisecomment($langwisecomment): void
    {
        $this->langwisecomment = (int)$langwisecomment;
    }

    /**
     * Get aioptions
     *
     * @return string
     */
    public function getAioptions(): string
    {
        return $this->aioptions;
    }

    /**
     * Set aioptions
     *
     * @param string $aioptions aioptions
     */
    public function setAioptions(string $aioptions): void
    {
        $this->aioptions = $aioptions;
    }
}
