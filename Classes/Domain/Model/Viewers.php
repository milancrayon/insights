<?php
declare(strict_types=1);

namespace T3element\Insights\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Viewers extends AbstractEntity
{
    protected string $ipAddress = '';
    protected string $userAgent = '';

    /**
     * @var int
     */
    protected int $post = 0;

    /**
     * Get ipAddress
     *
     * @return string
     */
    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress ipAddress
     */
    public function setIpAddress($ipAddress): void
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * Get userAgent
     *
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * Set userAgent
     *
     * @param string $userAgent userAgent
     */
    public function setUserAgent($userAgent): void
    {
        $this->userAgent = $userAgent;
    }
    public function getPost(): int
    {
        return $this->post;
    }

    public function setPost(int $post): void
    {
        $this->post = $post;
    }


}
