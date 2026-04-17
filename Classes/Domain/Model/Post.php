<?php
declare(strict_types=1);

namespace T3element\Insights\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use T3element\Insights\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;

class Post extends AbstractEntity
{
    /**
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * slug
     *
     * @var string
     */
    protected $slug = '';

    /**
     * alternativetitle
     *
     * @var string
     */
    protected $alternativetitle = '';

    /**
     * metakeyword
     *
     * @var string
     */
    protected $metakeyword = '';
    /**
     * postschema
     *
     * @var string
     */
    protected $postschema = '';

    /**
     * metadescription
     *
     * @var string
     */
    protected $metadescription = '';

    /**
     * teaser
     *
     * @var string
     */
    protected $teaser = '';

    /**
     * description
     *
     * @var string
     */
    protected $description = '';

    /**
     * publishdate
     *
     * @var \DateTime
     */
    protected $publishdate;

    /**
     * archivedate
     *
     * @var \DateTime
     */
    protected $archivedate;

    /**
     * thumbnail
     * 
     * @var ObjectStorage<FileReference>
     */
    protected $thumbnail;

    /** 
     *
     * @var ?ObjectStorage<Category>
     */
    public ?ObjectStorage $category = null;



    /**
     * viewers
     *
     * @var int
     */
    protected $viewers = 0;

    /**
     * author
     *
     * @var string
     */
    protected $author = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\T3element\Insights\Domain\Model\Tags>
     */
    protected $tags;

    /**
     * relatedfiles
     * 
     * @var ObjectStorage<FileReference>
     */
    protected $relatedfiles;

    /**
     * previouspost
     *
     * @var string
     */
    protected $previouspost = '';

    /**
     * nextpost
     *
     * @var string
     */
    protected $nextpost = '';

    /**
     * relatedposts
     *
     * @var string
     */
    protected $relatedposts = '';

    /** @var ObjectStorage<TtContent> */
    protected ObjectStorage $celement;


    /** 
     *
     * @var int
     */
    protected $poststatus = 0;

    /** 
     *
     * @var int
     */
    protected $recommended = 0;

    protected ?\DateTime $crdate = null;
    protected ?\DateTime $tstamp = null;

    public function getCrdate(): ?\DateTime
    {
        return $this->crdate;
    }

    public function setCrdate(\DateTime $crdate): void
    {
        $this->crdate = $crdate;
    }

    public function getTstamp(): ?\DateTime
    {
        return $this->tstamp;
    }

    public function setTstamp(\DateTime $tstamp): void
    {
        $this->tstamp = $tstamp;
    }

    public function __construct()
    {
        $this->initializeObject();
    }

    public function initializeObject(): void
    {
        $this->thumbnail = new ObjectStorage();
        $this->tags = new ObjectStorage();
        $this->relatedfiles = new ObjectStorage();
        $this->category = new ObjectStorage();
        $this->celement = new ObjectStorage();
    }
    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
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
     * Get alternativetitle
     *
     * @return string
     */
    public function getAlternativetitle(): string
    {
        return $this->alternativetitle;
    }

    /**
     * Set alternativetitle
     *
     * @param string $alternativetitle alternativetitle
     */
    public function setAlternativetitle($alternativetitle): void
    {
        $this->alternativetitle = $alternativetitle;
    }

    /**
     * Get metakeyword
     *
     * @return string
     */
    public function getMetakeyword(): string
    {
        return $this->metakeyword;
    }

    /**
     * Set metakeyword
     *
     * @param string $metakeyword metakeyword
     */
    public function setMetakeyword($metakeyword): void
    {
        $this->metakeyword = $metakeyword;
    }
    /**
     * Get postschema
     *
     * @return string
     */
    public function getPostschema(): string
    {
        return $this->postschema;
    }

    /**
     * Set postschema
     *
     * @param string $postschema postschema
     */
    public function setPostschema($postschema): void
    {
        $this->postschema = $postschema;
    }

    /**
     * Get metadescription
     *
     * @return string
     */
    public function getMetadescription(): string
    {
        return $this->metadescription;
    }

    /**
     * Set metadescription
     *
     * @param string $metadescription metadescription
     */
    public function setMetadescription($metadescription): void
    {
        $this->metadescription = $metadescription;
    }

    /**
     * Get teaser
     *
     * @return string
     */
    public function getTeaser(): string
    {
        return $this->teaser;
    }

    /**
     * Set teaser
     *
     * @param string $teaser teaser
     */
    public function setTeaser($teaser): void
    {
        $this->teaser = $teaser;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * Get publishdate
     *
     * @return \DateTime|null
     */
    public function getPublishdate(): ?\DateTime
    {
        return $this->publishdate;
    }

    /**
     * Set publishdate
     *
     * @param \DateTime $publishdate
     */
    public function setPublishdate(\DateTime $publishdate): void
    {
        $this->publishdate = $publishdate;
    }

    /**
     * Get archivedate
     *
     * @return \DateTime|null
     */
    public function getArchivedate(): ?\DateTime
    {
        return $this->archivedate;
    }

    /**
     * Set archivedate
     *
     * @param \DateTime $archivedate
     */
    public function setArchivedate(\DateTime $archivedate): void
    {
        $this->archivedate = $archivedate;
    }

    public function addThumbnail(FileReference $thumbnail): void
    {
        $this->thumbnail = $this->getThumbnail();
        $this->thumbnail->attach($thumbnail);
    }
    /**
     * @return ObjectStorage<FileReference>
     */
    public function getThumbnail(): ObjectStorage
    {
        return $this->thumbnail;
    }
    public function removeThumbnail(FileReference $thumbnail): void
    {
        $this->thumbnail = $this->getThumbnail();
        $this->thumbnail->detach($thumbnail);
    }
    /**
     * @param ObjectStorage<FileReference> $thumbnail
     */
    public function setThumbnail(ObjectStorage $thumbnail): void
    {
        $this->thumbnail = $thumbnail;
    }

    /**
     * Add category 
     */
    public function addCategory(Category $category): void
    {
        $this->category->attach($category);
    }
    /**
     * Set category
     *
     * @param ObjectStorage<Category> $category
     */
    public function setCategory(ObjectStorage $category): void
    {
        $this->category = $category;
    }
    /**
     * Get category
     *
     * @return ?ObjectStorage<Category>
     */
    public function getCategory(): ?ObjectStorage
    {
        return $this->category;
    }
    /**
     * Remove category  
     */
    public function removeCategory(Category $category): void
    {
        $this->category->detach($category);
    }


    /**
     * Get viewers
     *
     * @return integer
     */
    public function getViewers(): int
    {
        return $this->viewers;
    }

    /**
     * Set viewers
     *
     * @param integer $viewers viewers
     */
    public function setViewers($viewers): void
    {
        $this->viewers = $viewers;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Set author
     *
     * @param string $author author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }
    /**
     * @return ObjectStorage<Tags>
     */
    public function getTags(): ObjectStorage
    {
        return $this->tags;
    }

    /**
     * @param ObjectStorage<Tags> $tags
     */
    public function setTags(ObjectStorage $tags): void
    {
        $this->tags = $tags;
    }

    /**
     * Adds a single tag
     */
    public function addTag(Tags $tag): void
    {
        $this->tags->attach($tag);
    }

    /**
     * Removes a single tag
     */
    public function removeTag(Tags $tag): void
    {
        $this->tags->detach($tag);
    }
    public function addRelatedfiles(FileReference $relatedfiles): void
    {
        $this->relatedfiles = $this->getRelatedfiles();
        $this->relatedfiles->attach($relatedfiles);
    }
    /**
     * @return ObjectStorage<FileReference>
     */
    public function getRelatedfiles(): ObjectStorage
    {
        return $this->relatedfiles;
    }
    public function removeRelatedfiles(FileReference $relatedfiles): void
    {
        $this->relatedfiles = $this->getRelatedfiles();
        $this->relatedfiles->detach($relatedfiles);
    }
    /**
     * @param ObjectStorage<FileReference> $relatedfiles
     */
    public function setRelatedfiles(ObjectStorage $relatedfiles): void
    {
        $this->relatedfiles = $relatedfiles;
    }

    /**
     * Get previouspost
     *
     * @return string
     */
    public function getPreviouspost(): string
    {
        return $this->previouspost;
    }

    /**
     * Set previouspost
     *
     * @param string $previouspost previouspost
     */
    public function setPreviouspost($previouspost): void
    {
        $this->previouspost = $previouspost;
    }

    /**
     * Get nextpost
     *
     * @return string
     */
    public function getNextpost(): string
    {
        return $this->nextpost;
    }

    /**
     * Set nextpost
     *
     * @param string $nextpost nextpost
     */
    public function setNextpost($nextpost): void
    {
        $this->nextpost = $nextpost;
    }

    /**
     * Get relatedposts
     *
     * @return array
     */
    public function getRelatedposts(): array
    {
        if ($this->relatedposts != "") {
            return explode(",", $this->relatedposts);
        } else {
            return [];
        }
    }

    /**
     * Set relatedposts
     *
     * @param string $relatedposts relatedposts
     */
    public function setRelatedposts($relatedposts): void
    {
        $this->relatedposts = $relatedposts;
    }


    /**
     * Get content elements
     *
     * @return ObjectStorage<TtContent>
     */
    public function getCelement(): ?ObjectStorage
    {
        return $this->celement;
    }

    /**
     * Set  list
     *
     * @param ObjectStorage<TtContent> $celement  
     */
    public function setCelement($celement): void
    {
        $this->celement = $celement;
    }

    /**
     * Adds to the record
     */
    public function addCelement(TtContent $celement): void
    {
        if ($this->getCelement() === null) {
            $this->celement = new ObjectStorage();
        }
        $this->celement->attach($celement);
    }

    /**
     * Get id list
     */
    public function getCelementIdList(): string
    {
        return $this->getIdOfCelement();
    }


    /**
     * Get translated id list  
     */
    public function getTranslatedCelementIdList(): string
    {
        return $this->getIdOfCelement(false);
    }

    /**
     * Get id list of non-nested  
     */
    public function getNonNestedCelementIdList(): string
    {
        return $this->getIdOfNonNestedCelement();
    }

    /**
     * Get translated id list of non-nested  
     */
    public function getTranslatedNonNestedCelementIdList(): string
    {
        return $this->getIdOfNonNestedCelement(false);
    }

    /**
     * Collect id list
     *
     * @param bool $original
     */
    protected function getIdOfCelement($original = true): string
    {
        $idList = [];
        $celement = $this->getCelement();
        if ($celement) {
            foreach ($celement as $cuselement) {
                if ($cuselement->getColPos() >= 0) {
                    $idList[] = $original ? $cuselement->getUid() : $cuselement->_getProperty('_localizedUid');
                }
            }
        }
        return implode(',', $idList);
    }

    /** 
     * @param bool $original
     */
    protected function getIdOfNonNestedCelement($original = true): string
    {
        $idList = [];
        $celement = $this->getCelement();
        if ($celement) {
            foreach ($celement as $cuselement) {
                if ($cuselement->getColPos() >= 0 && $cuselement->getTxContainerParent() === 0) {
                    $idList[] = $original ? $cuselement->getUid() : $cuselement->_getProperty('_localizedUid');
                }
            }
        }

        return implode(',', $idList);
    }

    /**
     * Get poststatus
     *
     * @return int
     */
    public function getPoststatus(): int
    {
        return $this->poststatus;
    }

    /**
     * Set poststatus
     *
     * @param int $poststatus poststatus
     */
    public function setPoststatus($poststatus): void
    {
        $this->poststatus = $poststatus;
    }
    /**
     * Get recommended
     *
     * @return int
     */
    public function getRecommended(): int
    {
        return $this->recommended;
    }

    /**
     * Set recommended
     *
     * @param int $recommended recommended
     */
    public function setRecommended($recommended): void
    {
        $this->recommended = $recommended;
    }

    public function getThumbnailListOnly(): array
    {
        $configuration = [FileReference::VIEW_LIST_ONLY];
        return $this->getThumbnailItemsByConfiguration($configuration);
    }
    public function getThumbnailDetailOnly(): array
    {
        $configuration = [FileReference::VIEW_DETAIL_ONLY];
        return $this->getThumbnailItemsByConfiguration($configuration);
    }
    public function getThumbnailListDetailOnly(): array
    {
        $configuration = [FileReference::VIEW_LIST_AND_DETAIL];
        return $this->getThumbnailItemsByConfiguration($configuration);
    }

    protected function getThumbnailItemsByConfiguration(array $list): array
    {
        $items = [];
        foreach ($this->getThumbnail() as $mediaItem) {
            /** @var FileReference $mediaItem */
            $configuration = (int) $mediaItem->getOriginalResource()->getProperty('insightview');
            // var_dump($list);
            if (in_array($configuration, $list, true)) {
                $items[] = $mediaItem;
            }
        }
        return $items;
    }

    /**
     * @return Category|null
     */
    public function getPrimaryCategory(): ?Category
    {
        if ($this->category instanceof ObjectStorage && $this->category->count() > 0) {
            // Prefer a category that has a parent (more specific)
            foreach ($this->category as $cat) {
                if ($cat->getParentcategory() !== null) {
                    return $cat;
                }
            }
            // Fallback to the first one available
            return $this->category->current();
        }
        return null;
    }
}
