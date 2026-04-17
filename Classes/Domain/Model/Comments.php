<?php
declare(strict_types=1);

namespace T3element\Insights\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Comments extends AbstractEntity
{
    protected ?\DateTime $crdate = null;
    protected ?\DateTime $tstamp = null;
    
    /**
     * name
     *
     * @var string
     */
    protected $name = '';
    /**
     * clang
     *
     * @var int
     */
    protected $clang = 0;

    /**
     * email
     *
     * @var string
     */
    protected $email = '';

    /**
     * comment
     *
     * @var string
     */
    protected $comment = '';

    /**
     * post
     *
     * @var string
     */
    protected $post = '';

    /**
     * parentcomment
     *
     * @var string
     */
    protected $parentcomment = '';

    /**
     * status
     *
     * @var string
     */
    protected $status = '';
    
    /**
     * Get clang
     *
     * @return integer
     */
    public function getClang(): int
    {
        return $this->clang;
    }

    /**
     * Set clang
     *
     * @param integer $clang clang
     */
    public function setClang($clang): void
    {
        $this->clang = $clang;
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

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * Set comment
     *
     * @param string $comment comment
     */
    public function setComment($comment): void
    {
        $this->comment = $comment;
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
     * Get status
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param string $status status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }


    public function getCrdate(): ?\DateTime
    {
        return $this->crdate;
    }
    public function getTstamp(): ?\DateTime
    {
        return $this->tstamp;
    }
}
