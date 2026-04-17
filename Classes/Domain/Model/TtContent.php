<?php

declare(strict_types=1);

namespace T3element\Insights\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
class TtContent extends AbstractEntity
{
    protected string $CType = '';
    protected int $colPos = 0;
    protected int $txContainerParent = 0;
    protected ?string $header = null;
    protected ?string $bodytext = null;

    public function getCType(): string
    {
        return $this->CType;
    }
    public function getColPos(): int
    {
        return $this->colPos;
    }
    public function getTxContainerParent(): int
    {
        return $this->txContainerParent;
    }
    public function getHeader(): ?string
    {
        return $this->header;
    }
    public function getBodytext(): ?string
    {
        return $this->bodytext;
    }
}

