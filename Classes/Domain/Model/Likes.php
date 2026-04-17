<?php
declare(strict_types=1);

namespace T3element\Insights\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Likes extends AbstractEntity
{
    /**
     * ipaddress
     *
     * @var string
     */
    protected $ipaddress = '';

    /**
     * browser
     *
     * @var string
     */
    protected $browser = '';


     /**
     * post
     *
     * @var string
     */
    protected $post = '';


      /**
     *
     * @var int
     */
    protected $like = 0;

     /**
     *
     * @var int
     */
    protected $dislike = 0;
    
    /**
     * Get ipaddress
     *
     * @return string
     */
    public function getIpaddress(): string
    {
        return $this->ipaddress;
    }

    /**
     * Set ipaddress
     *
     * @param string $ipaddress ipaddress
     */
    public function setIpaddress($ipaddress): void
    {
        $this->ipaddress = $ipaddress;
    }
            
    /**
     * Get browser
     *
     * @return string
     */
    public function getBrowser(): string
    {
        return $this->browser;
    }

    /**
     * Set browser
     *
     * @param string $browser browser
     */
    public function setBrowser($browser): void
    {
        $this->browser = $browser;
    }
            
    /**
     * Get post
     *
     * @return string
     */
    public function getPost(): string
    {
        return $this->post;
    } 
             
    /**
     * Set post
     *
     * @param string $post post
     */
    public function setPost($post): void
    {
        $this->post = $post;
    }

     /**
     *
     * @return int
     */
    public function getLike(): int
    {
        return $this->like;
    } 
             
    /**
     *
     * @param int $like like
     */
    public function setLike($like): void
    {
        $this->like = $like;
    }
     /**
     *
     * @return int
     */
    public function getDislike(): int
    {
        return $this->dislike;
    } 
             
    /**
     *
     * @param int $dislike dislike
     */
    public function setDislike($dislike): void
    {
        $this->dislike = $dislike;
    }
}
