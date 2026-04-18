<?php
declare(strict_types=1);

namespace T3element\Insights\Controller;

use DateTime;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Http\HtmlResponse;
use T3element\Insights\Domain\Repository\CategoryRepository;
use T3element\Insights\Domain\Repository\AuthorRepository;
use T3element\Insights\Domain\Repository\TagsRepository;
use T3element\Insights\Domain\Model\Category;
use T3element\Insights\Domain\Model\Post;
use T3element\Insights\Domain\Repository\PostRepository;
use T3element\Insights\Domain\Model\Likes;
use T3element\Insights\Domain\Repository\LikesRepository;
use T3element\Insights\Domain\Model\Comments;
use T3element\Insights\Domain\Repository\CommentsRepository;
use T3element\Insights\Domain\Model\Config;
use T3element\Insights\Domain\Repository\ConfigRepository;
use T3element\Insights\Domain\Model\Viewers;
use T3element\Insights\Domain\Repository\ViewersRepository;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry;
use T3element\Insights\PageTitle\DynamicTitleProvider;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;

/**
 * @extensionScannerIgnoreFile
 */
class InsightsController extends ActionController
{
    /**
     * authorRepository
     * 
     *
     * @var AuthorRepository
     */
    protected $authorRepository = null;
    /**
     * @param AuthorRepository $authorRepository
     */
    public function injectAuthorRepository(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }
    /**
     * viewersRepository
     *
     * @var ViewersRepository
     */
    protected $viewersRepository = null;
    /**
     * @param ViewersRepository $viewersRepository
     */
    public function injectViewersRepository(ViewersRepository $viewersRepository)
    {
        $this->viewersRepository = $viewersRepository;
    }

    /**
     * tagsRepository
     *
     * @var TagsRepository
     */
    protected $tagsRepository = null;
    /**
     * @param TagsRepository $tagsRepository
     */
    public function injectTagsRepository(TagsRepository $tagsRepository)
    {
        $this->tagsRepository = $tagsRepository;
    }

    /**
     * postRepository
     *
     * @var PostRepository
     */
    protected $postRepository = null;
    /**
     * @param PostRepository $postRepository
     */
    public function injectPostRepository(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * likesRepository
     *
     * @var LikesRepository
     */
    protected $likesRepository = null;
    /**
     * @param LikesRepository $likesRepository
     */
    public function injectLikesRepository(LikesRepository $likesRepository)
    {
        $this->likesRepository = $likesRepository;
    }

    /**
     * ConfigRepository
     *
     * @var ConfigRepository
     */
    protected $ConfigRepository = null;
    /**
     * @param ConfigRepository $ConfigRepository
     */
    public function injectConfigRepository(ConfigRepository $ConfigRepository)
    {
        $this->ConfigRepository = $ConfigRepository;
    }

    /**
     * commentsRepository
     *
     * @var CommentsRepository
     */
    protected $commentsRepository = null;
    /**
     * @param CommentsRepository $commentsRepository
     */
    public function injectCommentsRepository(CommentsRepository $commentsRepository)
    {
        $this->commentsRepository = $commentsRepository;
    }

    /**
     * categoryRepository
     *
     * @var CategoryRepository
     */
    protected $categoryRepository = null;
    /**
     * @param CategoryRepository $categoryRepository
     */
    public function injectCategoryRepository(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    protected PageRenderer $pageRenderer;

    protected PersistenceManager $persistenceManager;

    public function injectPersistenceManager(PersistenceManager $persistenceManager)
    {
        $this->persistenceManager = $persistenceManager;
    }

    protected Context $context;

    public function __construct(
        protected readonly ModuleTemplateFactory $moduleTemplateFactory,
        PageRenderer $pageRenderer,
        Context $context,
    ) {

        $this->pageRenderer = $pageRenderer;

        $this->context = $context;
    }


    public function postlistAction(): ResponseInterface
    {

        $settings = $this->settings;
        $this->view->assign('settings', $settings);
        $config = $this->ConfigRepository->findByUid(1);
        if ($config) {
            $this->view->assign('displayauthor', $config->getDisplayauthor());
        } else {
            $this->view->assign('displayauthor', 0);
        }

        $arguments = $this->request->getQueryParams();

        $page = isset($arguments['page']) ? (int) $arguments['page'] : 1;
        $pageSize = isset($arguments['pageSize']) ? (int) $arguments['pageSize'] : (int) $settings['pageSize'];

        $orders = null;
        if ($settings['orderBy'] && $settings['orderDir']) {
            if ($settings['orderDir'] == "asc") {
                $orders = [$settings['orderBy'] => QueryInterface::ORDER_ASCENDING];
            } else {
                $orders = [$settings['orderBy'] => QueryInterface::ORDER_DESCENDING];
            }
        }
        $_filters = array();
        $_filters['poststatus'] = 1;

        $items = [];
        $this->postRepository->setStorage((int) $settings['storePid']);
        $items = $this->postRepository->findBy($_filters, $orders);


        $paginator = new QueryResultPaginator(
            $items,
            $page,
            $pageSize
        );
        $pagination = new SimplePagination($paginator);
        $lst = 0;
        foreach ($paginator->getPaginatedItems() as $itm) {
            if ($itm->getThumbnailListOnly()) {
                $thumnbnail = $itm->getThumbnailListOnly();
            } else {
                $thumnbnail = $itm->getThumbnailListDetailOnly();
            }
            $this->view->assign('thumbnail_' . $lst, $thumnbnail);
            $author = $itm->getAuthor();
            if ($author != "") {
                $authorData = null;
                $authorData = $this->authorRepository->findByUid($author);
                if ($authorData) {
                    $author = $authorData->getName();
                    $this->view->assign('author_' . $lst, $author);
                }
            }
            $lst++;
        }
        $this->view->assignMultiple([
            'items' => $paginator->getPaginatedItems(),
            'pagination' => $pagination,
            'paginator' => $paginator,
            'page' => $page,
            'pageSize' => $pageSize,
            'nextPage' => $pagination->getNextPageNumber(),
            'previousPage' => $pagination->getPreviousPageNumber(),
        ]);
        return $this->htmlResponse();
    }
    public function recommendedAction(): ResponseInterface
    {

        $settings = $this->settings;
        $this->view->assign('settings', $settings);

        $page = 1;
        $pageSize = (int) $settings['maxpost'];

        $orders = null;
        if ($settings['orderBy'] && $settings['orderDir']) {
            if ($settings['orderDir'] == "asc") {
                $orders = [$settings['orderBy'] => QueryInterface::ORDER_ASCENDING];
            } else {
                $orders = [$settings['orderBy'] => QueryInterface::ORDER_DESCENDING];
            }
        }
        $_filters = array();
        $_filters['poststatus'] = 1;
        $_filters['recommended'] = 1;

        $items = [];
        $this->postRepository->setStorage((int) $settings['storePid']);
        $items = $this->postRepository->findBy($_filters, $orders);


        $paginator = new QueryResultPaginator(
            $items,
            $page,
            $pageSize
        );
        $lst = 0;
        foreach ($paginator->getPaginatedItems() as $itm) {
            if ($itm->getThumbnailListOnly()) {
                $thumnbnail = $itm->getThumbnailListOnly();
            } else {
                $thumnbnail = $itm->getThumbnailListDetailOnly();
            }
            $this->view->assign('thumbnail_' . $lst, $thumnbnail);
            $lst++;
        }
        $this->view->assignMultiple([
            'items' => $paginator->getPaginatedItems(),
        ]);
        return $this->htmlResponse();
    }

    public function categorylistAction(): ResponseInterface
    {
        $settings = $this->settings;
        $categories = [];
        if (!empty($settings['categories'])) {
            $uids = GeneralUtility::intExplode(',', $settings['categories'], true);
            $categories = $this->categoryRepository->findByUids($uids);
        }
        $this->view->assignMultiple([
            'categories' => $categories,
            'settings' => $settings
        ]);
        return $this->htmlResponse();
    }


    public function tagsAction(): ResponseInterface
    {

        $settings = $this->settings;
        $this->view->assign('settings', $settings);
        $orders = null;
        if ($settings['orderBy'] && $settings['orderDir']) {
            if ($settings['orderDir'] == "asc") {
                $orders = [$settings['orderBy'] => QueryInterface::ORDER_ASCENDING];
            } else {
                $orders = [$settings['orderBy'] => QueryInterface::ORDER_DESCENDING];
            }
        }
        $this->tagsRepository->setStorage((int) $settings['storePid']);
        $items = $this->tagsRepository->findBy([], $orders);
        $this->view->assign('items', $items);
        return $this->htmlResponse();
    }


    public function postdetailAction(?Post $item = null, ?Category $category = null): ResponseInterface
    {
        $settings = $this->settings;
        $this->view->assign('settings', $settings);
        $_params = $this->request->getQueryParams();
        $arguments = $this->request->getArguments();
        $site = $this->request->getAttribute('site');
        $siteSettings = $site->getSettings();
        $detailPid = (int) ($siteSettings->get('insightsDetailPid') ?? 0);
        $router = $site->getRouter();
        if (isset($settings['selectedRecord'])) {
            $uid = $settings['selectedRecord'];
        } else {
            $uid = isset($_params['uid']) ? $_params['uid'] : '';
            if (!$uid && isset($arguments['uid'])) {
                $uid = (int) $arguments['uid'];
            } else if (!$uid && isset($arguments['item'])) {
                $uid = (int) $arguments['item'];
            } else if (!$uid && isset($arguments['tx_insights_postdetail']['item'])) {
                $uid = (int) $arguments['tx_insights_postdetail']['item'];
            }
        }
        if ($item instanceof Post) {
            $uid = (int) $item->getUid();
        }
        if ($uid != "") {
            $item = null;
            $this->viewserOfPostUpdate((int) $uid);
            $item = $this->postRepository->findByUid((int) $uid);

            if ($item) {
                $this->view->assign('item', $item);

                $registry = GeneralUtility::makeInstance(MetaTagManagerRegistry::class);

                $dt = GeneralUtility::makeInstance(DynamicTitleProvider::class);
                if ($item->getTitle()) {
                    $dt->setTitleByPost($item);
                }

                if ($item->getMetadescription()) {
                    $descriptionManager = $registry->getManagerForProperty('description');
                    $descriptionManager->removeProperty('description');
                    $descriptionManager->addProperty(
                        'description',
                        $item->getMetadescription()
                    );

                }
                if ($item->getMetakeyword()) {
                    $kyManager = $registry->getManagerForProperty('keywords');
                    $kyManager->addProperty('keywords', $item->getMetakeyword());
                }


                $_likedata = $this->getCurrentLikestatus($uid);
                $this->view->assign('likes', $_likedata['likes']);
                $this->view->assign('dislikes', $_likedata['dislikes']);
                $this->view->assign('isliked', $_likedata['isliked']);
                $this->view->assign('isdisliked', $_likedata['isdisliked']);

                $config = $this->ConfigRepository->findByUid(1);
                if ($config) {
                    if ($config->getDisplaycomment()) {
                        $_comments = $this->getComments($uid);
                        $this->view->assign('comments', $_comments);
                    }
                    $this->view->assign('displaycomment', $config->getDisplaycomment());
                    $this->view->assign('nextprvbtn', $config->getNextprvbtn());
                    $this->view->assign('socialshare', $config->getSocialshare());
                    $this->view->assign('addcomments', $config->getAddcomments());
                    $this->view->assign('displayauthor', $config->getDisplayauthor());
                } else {
                    $this->view->assign('displaycomment', 0);
                    $this->view->assign('nextprvbtn', 0);
                    $this->view->assign('socialshare', 0);
                    $this->view->assign('addcomments', 0);
                    $this->view->assign('displayauthor', 0);
                }


                $currentUrl = (string) $GLOBALS['TYPO3_REQUEST']->getUri();
                $this->view->assign('currentUrl', $currentUrl);


                if ($config) {
                    if ($config->getSocialmedia()) {
                        $this->view->assign('socialmedia', json_decode($config->getSocialmedia()));
                    }
                }


                $author = $item->getAuthor();

                if ($author != "") {
                    $authorData = null;
                    $authorData = $this->authorRepository->findByUid((int) $author);
                    $this->view->assign('author', $authorData);
                }

                $_tags = $item->getTags();

                $this->view->assign('tags', $_tags);

                $previouspost = $item->getPreviouspost();

                if ($previouspost != "" && $previouspost != 0) {
                    $previouspostData = null;
                    $previouspostData = $this->postRepository->findByUid((int) $previouspost);
                    if ($previouspostData) {
                        $previouspostDataurl = (string) $router->generateUri(
                            $detailPid,
                            [
                                'tx_insights_postdetail' => [
                                    'action' => 'postdetail',
                                    'controller' => 'Insights',
                                    'item' => $previouspostData->getUid(),
                                    'category' => $previouspostData->getPrimaryCategory() ? $previouspostData->getPrimaryCategory()->getUid() : 0
                                ]
                            ]
                        );
                        $this->view->assign('previouspost', $previouspostData);
                        $this->view->assign('previouspostDataurl', $previouspostDataurl);
                    } else {
                        $this->view->assign('previouspostDataurl', "#");
                    }
                } else {
                    $this->view->assign('previouspostDataurl', "#");
                }

                $nextpost = $item->getNextpost();
                if ($nextpost != "" && $nextpost != 0) {
                    $nextpostData = null;
                    $nextpostData = $this->postRepository->findByUid((int) $nextpost);
                    if ($nextpostData) {
                        $nextpostDataurl = (string) $router->generateUri(
                            $detailPid,
                            [
                                'tx_insights_postdetail' => [
                                    'action' => 'postdetail',
                                    'controller' => 'Insights',
                                    'item' => $nextpostData->getUid(),
                                    'category' => $nextpostData->getPrimaryCategory() ? $nextpostData->getPrimaryCategory()->getUid() : 0
                                ]
                            ]
                        );
                        $this->view->assign('nextpost', $nextpostData);
                        $this->view->assign('nextpostDataurl', $nextpostDataurl);
                    } else {
                        $this->view->assign('nextpostDataurl', "#");
                    }
                } else {
                    $this->view->assign('nextpostDataurl', "#");
                }

                if ($item->getThumbnailDetailOnly()) {
                    $thumnbnail = $item->getThumbnailDetailOnly();
                } else {
                    $thumnbnail = $item->getThumbnailListDetailOnly();
                }
                $this->view->assign('thumbnail', $thumnbnail);

                $_relatedposts = $item->getRelatedposts();
                $_relatedpostsData = [];
                if ($_relatedposts) {
                    $i = 0;
                    foreach ($_relatedposts as $uid) {
                        $_result = $this->postRepository->findByUid($uid);
                        if ($_result) {
                            $author = $_result->getAuthor();

                            if ($author != "") {
                                $authorData = $this->authorRepository->findByUid((int) $author);
                                if ($authorData) {
                                    $author = $authorData->getName();
                                    $this->view->assign('relatedposts_' . $i . '_author', $author);
                                }
                            }

                            $previouspost = $_result->getPreviouspost();

                            if ($previouspost != "") {
                                $previouspostData = $this->postRepository->findByUid((int) $previouspost);
                                if ($previouspostData) {
                                    $previouspost = $previouspostData->getTitle();
                                    $this->view->assign('relatedposts_' . $i . '_previouspost', $previouspost);
                                }
                            }

                            $nextpost = $_result->getNextpost();

                            if ($nextpost != "") {
                                $nextpostData = $this->postRepository->findByUid((int) $nextpost);
                                if ($nextpostData) {
                                    $nextpost = $nextpostData->getTitle();
                                    $this->view->assign('relatedposts_' . $i . '_nextpost', $nextpost);
                                }
                            }

                            $_relatedpostsData[] = $_result;
                            $i++;
                        }
                    }
                    $this->view->assign('relatedposts', $_relatedpostsData);
                }

            }
        }

        return $this->htmlResponse();
    }


    public function commentformAction(): ResponseInterface
    {

        $settings = $this->settings;
        $this->view->assign('settings', $settings);
        $_params = $this->request->getQueryParams();
        $arguments = $this->request->getArguments();
        $postid = (int) $_params['uid'];
        if (!$postid) {
            $postid = (int) $arguments['uid'];
        }
        $this->view->assign('postid', $postid);
        return $this->htmlResponse();
    }

    public function commentformSubmitAction(): ResponseInterface
    {
        $settings = $this->settings;
        $this->view->assign('settings', $settings);
        $_datas = $this->request->getArguments();
        $formData = $_datas;
        if (isset($_datas['commentform'])) {
            $formData = array_merge($_datas, $_datas['commentform']);
        }
        // var_dump(value: $_datas);
        // exit();
        $pageUid = (int) $this->request->getAttribute('routing')->getPageId();
        $site = $this->request->getAttribute('site');
        $router = $site->getRouter();
        $url = "";
        if (
            $formData
            && trim($formData['name'] ?? '') !== ''
            && trim($formData['email'] ?? '') !== ''
            && trim($formData['comment'] ?? '') !== ''
        ) {
            $_newobj = new Comments();
            $_newobj->setName($formData['name']);
            $_newobj->setEmail($formData['email']);
            $_newobj->setComment($formData['comment']);

            if ($formData['post'] && $formData['post'] != "") {
                if (gettype($formData['post']) == "array") {
                    $_newobj->setPost(implode(",", $formData['post']));
                } else {
                    $_newobj->setPost($formData['post']);
                }
            }

            $targetPid = (int) $formData['storePid'];
            $_newobj->setStatus("pending");
            $languageUid = (int) GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('language', 'id');
            $_newobj->setClang($languageUid);
            $_newobj->setPid($targetPid);
            $this->commentsRepository->add($_newobj);
            $this->persistenceManager->persistAll();
            $this->addFlashMessage('Your comment has been submitted and is pending approval.', '', ContextualFeedbackSeverity::OK);
            $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
            $uriBuilder->setRequest($this->request);
            if (isset($formData['thankPid']) && $formData['thankPid'] != "") {
                $url = $uriBuilder->reset()->setTargetPageUid((int) $formData['thankPid'])->setArguments(['uid' => $formData['post']])->build();
            } else {
                $url = (string) $router->generateUri(
                    (int) ($site->getSettings()->get('insightsDetailPid') ?? 0),
                    [
                        'tx_insights_postdetail' => [
                            'action' => 'postdetail',
                            'controller' => 'Insights',
                            'item' => (int) $formData['post'],
                            'category' => $this->postRepository->findByUid((int) $formData['post'])?->getPrimaryCategory()?->getUid() ?? 0
                        ]
                    ]
                );
            }
            return $this->redirectToUri($url);
        }

        if (isset($formData['post']) && $formData['post'] != "") {
            $url = (string) $router->generateUri(
                (int) ($site->getSettings()->get('insightsDetailPid') ?? 0),
                [
                    'tx_insights_postdetail' => [
                        'action' => 'postdetail',
                        'controller' => 'Insights',
                        'item' => (int) $formData['post'],
                        'category' => $this->postRepository->findByUid((int) $formData['post'])?->getPrimaryCategory()?->getUid() ?? 0
                    ]
                ]
            );
        } else {
            $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
            $uriBuilder->setRequest($this->request);
            $url = $uriBuilder->reset()->setTargetPageUid($pageUid)->build();
        }
        $this->addFlashMessage('Please fill in all required fields (Name, Email, Comment).', '', ContextualFeedbackSeverity::ERROR);
        return $this->redirectToUri($url);
    }

    public function postfilterAction(): ResponseInterface
    {
        $settings = $this->settings;
        $this->view->assign('settings', $settings);
        $arguments = $this->request->getQueryParams();
        if (isset($settings['storePid'])) {
            $this->categoryRepository->setStorage((int) $settings['storePid']);
        }
        $categories = $this->categoryRepository->getCategoryTree();
        $this->view->assign('categories', $categories);
        if (isset($arguments['category']) && $arguments['category'] != "") {
            $this->view->assign('category_res', $arguments['category']);
        } else {
            $this->view->assign('category_res', '');
        }
        if (isset($arguments['tags']) && $arguments['tags'] != "") {
            $this->view->assign('tags_res', $arguments['tags']);
        } else {
            $this->view->assign('tags_res', '');
        }
        if (isset($arguments['search']) && $arguments['search'] != "") {
            $this->view->assign('search_res', $arguments['search']);
        } else {
            $this->view->assign('search_res', '');
        }
        $_tags = [];
        $_tags[''] = "Select";
        $_tdata = null;

        $this->tagsRepository->setStorage();
        $_tdata = $this->tagsRepository->findAll();
        if ($_tdata) {
            foreach ($_tdata as $itm) {
                $_tags[$itm->getUid()] = $itm->getTitle();
            }
        }
        $this->view->assign('tags', $_tags);
        return $this->htmlResponse();
    }
    public function postfilterSubmitAction(): ResponseInterface
    {
        $settings = $this->settings;
        $this->view->assign('settings', $settings);
        $_data = $this->request->getArguments();
        if ($_data['filterResultPid']) {
            $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
            $uriBuilder->setRequest($this->request);
            $url = $uriBuilder->reset()->setTargetPageUid((int) $_data['filterResultPid'])->setArguments($_data['postfilter']);
            $uri = $url->build();
            return $this->redirectToUri($uri);
        }
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $uriBuilder->setRequest($this->request);
        $pageUid = (int) $this->request->getAttribute('routing')->getPageId();
        $uriBuilder->reset()->setTargetPageUid($pageUid);
        $url = $uriBuilder->build();
        return $this->redirectToUri($url);
    }
    public function filterresultlistAction(): ResponseInterface
    {
        $settings = $this->settings;
        $this->view->assign('settings', $settings);
        $_Dtaa = $this->request->getQueryParams();
        $_tags = [];
        if (isset($_Dtaa['tags']) && $_Dtaa['tags'] != "") {
            $_tags[] = $_Dtaa['tags'];
        }
        $_category = isset($_Dtaa['category']) ? (int) $_Dtaa['category'] : null;
        $page = isset($_Dtaa['page']) ? (int) $_Dtaa['page'] : 1;
        $pageSize = isset($_Dtaa['pageSize']) ? (int) $_Dtaa['pageSize'] : (int) $settings['pageSize'];
        $search = isset($_Dtaa['search']) ? $_Dtaa['search'] : null;
        unset($_Dtaa['page']);
        $this->view->assign('currentQueryParams', $_Dtaa);

        $config = $this->ConfigRepository->findByUid(1);
        if ($config instanceof \T3element\Insights\Domain\Model\Config) {
            $this->view->assign('displayauthor', $config->getDisplayauthor());
        }

        $site = $this->request->getAttribute('site');
        $router = $site->getRouter();
        $this->view->assign('currentQueryParams', $this->request->getQueryParams());

        $items = null;
        $postUrls = [];

        $items = $this->postRepository->findByCategoryAndTags(
            (int) $_category,
            $_tags,
            $search,
            $settings['orderBy'],
            $settings['orderDir']
        );

        if ($items) {
            $this->view->assign('items', $items);
            $paginator = new QueryResultPaginator(
                $items,
                $page,
                $pageSize
            );

            $pagination = new SimplePagination($paginator);
            $lst = 0;
            foreach ($paginator->getPaginatedItems() as $itm) {
                // Pre-calculate sparkling URL
                $postUrls[$itm->getUid()] = (string) $router->generateUri(
                    (int) ($site->getSettings()->get('insightsDetailPid') ?? 0),
                    [
                        'tx_insights_postdetail' => [
                            'action' => 'postdetail',
                            'controller' => 'Insights',
                            'item' => $itm->getUid(),
                            'category' => $itm->getPrimaryCategory() ? $itm->getPrimaryCategory()->getUid() : 0
                        ]
                    ]
                );

                if ($itm->getThumbnailListOnly()) {
                    $thumnbnail = $itm->getThumbnailListOnly();
                } else {
                    $thumnbnail = $itm->getThumbnailListDetailOnly();
                }
                $this->view->assign('thumbnail_' . $lst, $thumnbnail);

                $author = $itm->getAuthor();
                if ($author != "") {
                    $authorData = null;
                    $authorData = $this->authorRepository->findByUid($author);
                    if ($authorData) {
                        $author = $authorData->getName();
                        $this->view->assign('author_' . $lst, $author);
                    }
                }
                $lst++;
            }

            $totalPages = (int) ceil($items->count() / $pageSize);
            $pageNumbers = range(1, $totalPages);

            $pageLinks = [];
            foreach ($pageNumbers as $pageNumber) {
                $pageLinks[$pageNumber] = array_merge($_Dtaa, ['page' => $pageNumber]);
            }


            $previousPageParams = $pagination->getPreviousPageNumber() ? array_merge($_Dtaa, ['page' => $pagination->getPreviousPageNumber()]) : null;
            $nextPageParams = $pagination->getNextPageNumber() ? array_merge($_Dtaa, ['page' => $pagination->getNextPageNumber()]) : null;

            $this->view->assignMultiple([
                'items' => $paginator->getPaginatedItems(),
                'pagination' => $pagination,
                'paginator' => $paginator,
                'page' => $page,
                'pageSize' => $pageSize,
                'nextPage' => $pagination->getNextPageNumber(),
                'previousPage' => $pagination->getPreviousPageNumber(),
                'pageLinks' => $pageLinks,
                'previousPageParams' => $previousPageParams,
                'nextPageParams' => $nextPageParams,
                'postUrls' => $postUrls,
            ]);
        }

        return $this->htmlResponse();
    }

    public function categoryinsightsAction(?Category $category = null): ResponseInterface
    {
        $settings = $this->settings;
        $this->view->assign('settings', $settings);
        $arguments = $this->request->getQueryParams();
        $_Dtaa = $arguments;

        $pageSize = isset($arguments['pageSize']) ? (int) $arguments['pageSize'] : (int) $settings['pageSize'];
        $page = (int) ($arguments['page'] ?? 1);
        $page = $page > 0 ? $page : 1;

        $orderBy = $arguments['orderBy'] ?? $settings['orderBy'] ?? 'crdate';
        $orderDir = strtolower($arguments['orderDir'] ?? $settings['orderDir'] ?? 'desc');

        $orderDirection = ($orderDir === 'asc')
            ? QueryInterface::ORDER_ASCENDING
            : QueryInterface::ORDER_DESCENDING;

        $items = [];
        $categoryUids = [];
        $this->categoryRepository->setStorage(0);

        // Fallback for category resolution: Check own argument, then check other namespaces
        if ($category === null) {
            $catArg = $this->request->hasArgument('category') ? $this->request->getArgument('category') : null;

            // If No direct plugin argument, search in all namespaces (including recovering from 'item' key)
            if ($catArg === null) {
                foreach ($arguments as $key => $val) {
                    if (str_starts_with($key, 'tx_insights') && is_array($val)) {
                        // 1. Check for 'category' key in other namespaces
                        if (isset($val['category'])) {
                            $catArg = $val['category'];
                            break;
                        }
                        // 2. Collision Recovery: Check if 'item' key might actually be a category UID
                        // (Address Case 1 where child-category 136 is mis-routed as 'item')
                        if (isset($val['item']) && is_numeric($val['item'])) {
                            $testCategory = $this->categoryRepository->findByUid((int) $val['item']);
                            if ($testCategory) {
                                $category = $testCategory;
                                break;
                            }
                        }
                    }
                }
                // 3. Check for a flat 'category' parameter as last resort
                if ($category === null && $catArg === null && isset($arguments['category'])) {
                    $catArg = $arguments['category'];
                }
            }

            // Resolve from $catArg if we found one but don't have $category yet
            if ($category === null && $catArg) {
                if (is_numeric($catArg)) {
                    $category = $this->categoryRepository->findByUid((int) $catArg);
                } elseif (is_string($catArg)) {
                    // Try to find by slug specifically, then by title
                    $category = $this->categoryRepository->findOneBy(['slug' => $catArg]);
                    if (!$category) {
                        $category = $this->categoryRepository->findOneBy(['title' => $catArg]);
                    }
                }
            }
        }

        $this->view->assign('activeCategory', $category);

        if ($category !== null) {
            // Set dynamic page title
            $titleProvider = GeneralUtility::makeInstance(DynamicTitleProvider::class);
            $titleProvider->setTitle($category->getTitle());

            // Build recursive tree starting from current category
            $category->childs = $this->categoryRepository->getCategoryTree($category->getUid());

            $pageId = (int) $this->request->getAttribute('routing')->getPageId();
            $detailPid = (int) ($settings['detailPid'] ?? $pageId);

            // Map categories with their posts and detail URLs for tree rendering
            $treeWithPosts = $this->mapCategoriesWithPosts([$category], $detailPid, 0, []);
            $this->view->assign('categoryTree', $treeWithPosts[0] ?? null);

            // Still provide flat items list just in case needed by other templates
            $categoryUids = $this->categoryRepository->getAllChildUids($category);
            $items = $this->postRepository->findByCategories($categoryUids, $orderBy, $orderDirection);
            $this->view->assign('items', $items);
        }

        return $this->htmlResponse();
    }

    protected function buildCategoryTreeData($category, int $maxpost): array
    {
        $posts = $this->postRepository->findByCategory($category, $maxpost);
        $processedPosts = [];
        foreach ($posts as $post) {
            $processedPosts[] = [
                'object' => $post,
                'thumbnails' => $post->getThumbnailListOnly() ?: $post->getThumbnailListDetailOnly()
            ];
        }

        $children = $this->categoryRepository->findBy(['parentcategory' => $category]);
        $childrenData = [];
        foreach ($children as $child) {
            $childrenData[] = $this->buildCategoryTreeData($child, $maxpost);
        }

        return [
            'category' => $category,
            'posts' => $processedPosts,
            'children' => $childrenData
        ];
    }

    public function viewserOfPostUpdate($postUid)
    {
        $ip = GeneralUtility::getIndpEnv('REMOTE_ADDR');
        $userAgent = GeneralUtility::getIndpEnv('HTTP_USER_AGENT');

        $_viewers = $this->viewersRepository->findBy([
            'post' => $postUid,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
        ]);



        if (sizeof($_viewers) === 0) {
            $_newobj = new Viewers();
            $_newobj->setPost($postUid);
            $_newobj->setIpAddress($ip);
            $_newobj->setUserAgent($userAgent);
            $this->viewersRepository->add($_newobj);
        }

        $totalviewers = $this->viewersRepository->findBy([
            'post' => $postUid
        ]);

        $_post = $this->postRepository->findByUid($postUid);
        if ($_post) {
            $_post->setViewers(sizeof($totalviewers));
            $this->postRepository->update($_post);
        }

    }
    public function likeAction(): ResponseInterface
    {
        $uid = (int) $this->request->getArgument('uid');
        $ip = GeneralUtility::getIndpEnv('REMOTE_ADDR');
        $userAgent = GeneralUtility::getIndpEnv('HTTP_USER_AGENT');
        $pageUid = (int) $this->request->getAttribute('routing')->getPageId();

        $_like = $this->likesRepository->findBy([
            'post' => $uid,
            'ipaddress' => $ip,
            'browser' => $userAgent,
        ]);

        if (sizeof($_like)) {
            $like = $_like->getFirst();
            $like->setDislike(0);
            $like->setLike(1);
            $this->likesRepository->update($like);
        } else {
            $_newobj = new Likes();
            $_newobj->setPost((string) $uid);
            $_newobj->setIpaddress($ip);
            $_newobj->setBrowser($userAgent);
            $_newobj->setLike(1);
            $_newobj->setDislike(0);
            $this->likesRepository->add($_newobj);
        }
        $this->persistenceManager->persistAll();

        $uriBuilder = $this->uriBuilder;
        $uriBuilder->reset()
            ->setTargetPageUid($pageUid)
            ->setArguments([
                'tx_insights_postdetail' => [
                    'item' => $uid,
                    'category' => $this->postRepository->findByUid($uid)?->getPrimaryCategory()
                ]
            ]);

        $url = $uriBuilder->build();
        return $this->redirectToUri($url);
    }
    public function dislikeAction(): ResponseInterface
    {
        $uid = (int) $this->request->getArgument('uid');
        $ip = GeneralUtility::getIndpEnv('REMOTE_ADDR');
        $userAgent = GeneralUtility::getIndpEnv('HTTP_USER_AGENT');
        $pageUid = (int) $this->request->getAttribute('routing')->getPageId();
        $_like = $this->likesRepository->findBy([
            'post' => $uid,
            'ipaddress' => $ip,
            'browser' => $userAgent,
        ]);


        if (sizeof($_like)) {
            $like = $_like->getFirst();
            $like->setDislike(1);
            $like->setLike(0);
            $this->likesRepository->update($like);

        } else {
            $_newobj = new Likes();
            $_newobj->setPost((string) $uid);
            $_newobj->setIpaddress($ip);
            $_newobj->setBrowser($userAgent);
            $_newobj->setLike(0);
            $_newobj->setDislike(1);
            $this->likesRepository->add($_newobj);
        }
        $this->persistenceManager->persistAll();

        $uriBuilder = $this->uriBuilder;
        $uriBuilder->reset()
            ->setTargetPageUid($pageUid)
            ->setArguments([
                'tx_insights_postdetail' => [
                    'item' => $uid,
                    'category' => $this->postRepository->findByUid($uid)?->getPrimaryCategory()
                ]
            ]);

        $url = $uriBuilder->build();
        return $this->redirectToUri($url);
    }
    public function getCurrentLikestatus($uid)
    {
        $ip = GeneralUtility::getIndpEnv('REMOTE_ADDR');
        $userAgent = GeneralUtility::getIndpEnv('HTTP_USER_AGENT');

        $data = [];

        $this->likesRepository->setStorage();
        $_like = $this->likesRepository->findBy([
            'post' => $uid,
            'like' => 1,
            'dislike' => 0
        ]);
        $likecount = sizeof($_like);
        $data['likes'] = $likecount;

        if ($likecount > 0) {
            $data['likedata'] = $_like;
        }


        $_likedis = $this->likesRepository->findBy([
            'post' => $uid,
            'like' => 0,
            'dislike' => 1
        ]);
        $dislikecount = sizeof($_likedis);
        $data['dislikes'] = $dislikecount;

        $_cd = $this->likesRepository->findBy([
            'post' => $uid,
            'ipaddress' => $ip,
            'browser' => $userAgent,
        ]);

        if (sizeof($_cd)) {
            $_daata = $_cd->getFirst();
            $data['isliked'] = $_daata->getLike();
            $data['isdisliked'] = $_daata->getDislike();
        } else {
            $data['isliked'] = 0;
            $data['isdisliked'] = 0;
        }

        return $data;
    }
    public function getComments($uid)
    {

        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings
            ->setRespectSysLanguage(false)
            ->setIgnoreEnableFields(true);
        $this->commentsRepository->setDefaultQuerySettings($querySettings);
        $this->commentsRepository->setStorage();
        /** @var \T3element\Insights\Domain\Model\Config|null $config */
        $config = $this->ConfigRepository->findByUid(1);
        $_status = 0;
        if ($config && $config->getStatusToShow()) {
            $_status = "approved";
        }
        $filter = [];
        $filter['post'] = $uid;
        if ($_status) {
            $filter['status'] = $_status;
        }
        $languageUid = (int) GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('language', 'id');
        if ($config->getLangwisecomment()) {
            $filter['clang'] = $languageUid;
        }

        $comments = $this->commentsRepository->findBy($filter);
        return $comments;
    }


    public function breadcrumbsAction(): \Psr\Http\Message\ResponseInterface
    {
        $settings = $this->settings;
        $this->view->assign('settings', $settings);
        $arguments = $this->request->getQueryParams();
        $site = $this->request->getAttribute('site');
        $siteSettings = $site->getSettings();
        $categoryListingPid = (int) ($siteSettings->get('insightsCategoryPid') ?? 0);

        $postUid = $arguments['tx_insights_postdetail']['item'] ?? '';
        $activeItem = null;
        $mainCategory = null;
        $category = null;

        // 1. Resolve Category from URL arguments first (Collision-Aware)
        // This ensures hierarchical breadcrumbs follow the URL path.
        $catArg = null;
        foreach ($arguments as $key => $val) {
            if (str_starts_with($key, 'tx_insights') && is_array($val)) {
                if (isset($val['category'])) {
                    $catArg = $val['category'];
                    break;
                }
                // Collision Recovery: Check if 'item' key might actually be a category UID
                if (isset($val['item']) && is_numeric($val['item'])) {
                    $testCategory = $this->categoryRepository->findByUid((int) $val['item']);
                    if ($testCategory) {
                        $category = $testCategory;
                        break;
                    }
                }
            }
        }
        if ($category === null && $catArg) {
            if (is_numeric($catArg)) {
                $category = $this->categoryRepository->findByUid((int) $catArg);
            } else {
                $category = $this->categoryRepository->findOneBy(['slug' => $catArg]);
                if (!$category) {
                    $category = $this->categoryRepository->findOneBy(['title' => $catArg]);
                }
            }
        }

        // 2. Resolve active Post if present
        if (!empty($postUid)) {
            $activeItem = $this->postRepository->findByUid((int) $postUid);
        }

        // 3. Determine Context: Post Detail vs Category Listing
        if ($activeItem instanceof \T3element\Insights\Domain\Model\Post) {
            // Trail starts from the category specified in the URL, or falls back to post's primary category
            $mainCategory = $category ?? $activeItem->getCategory()->current();
        } elseif ($category instanceof \T3element\Insights\Domain\Model\Category) {
            // Category Listing context: Trail starts from parent, current title is the active crumb
            $mainCategory = $category->getParentcategory();
            $activeItem = $category;
        }

        if ($activeItem) {
            $this->view->assign('item', $activeItem);
            $breadcrumbs = [];

            if ($mainCategory) {
                $tempBreadcrumbs = [];
                $current = $mainCategory;
                while ($current !== null) {
                    $categoryUrl = $this->uriBuilder->reset()
                        ->setTargetPageUid($categoryListingPid)
                        ->setArguments([
                            'tx_insights_categoryinsights' => [
                                'category' => $current->getUid()
                            ]
                        ])
                        ->build();

                    array_unshift($tempBreadcrumbs, [
                        'title' => $current->getTitle(),
                        'uid' => $current->getUid(),
                        'url' => $categoryUrl
                    ]);
                    $current = $current->getParentcategory();
                }
                $breadcrumbs = $tempBreadcrumbs;
            }
            $this->view->assign('categoryBreadcrumbs', $breadcrumbs);

            // Populate likes/stats if it's a real Post
            $uidToStats = (int) $activeItem->getUid();
            if ($activeItem instanceof \T3element\Insights\Domain\Model\Post && $uidToStats > 0) {
                $_likedata = $this->getCurrentLikestatus($uidToStats);
                $this->view->assign('likes', $_likedata['likes'] ?? 0);
                $this->view->assign('dislikes', $_likedata['dislikes'] ?? 0);
                $this->view->assign('isliked', $_likedata['isliked'] ?? false);
                $this->view->assign('isdisliked', $_likedata['isdisliked'] ?? false);
            }
        }

        $this->view->assign('homePageId', $site->getRootPageId());
        return $this->htmlResponse();
    }

    public function quicksearchAction(): ResponseInterface
    {
        $settings = $this->settings;
        $this->view->assign('settings', $settings);
        $_params = $this->request->getQueryParams();
        $uid = (int) ($_params['tx_insights_postdetail']['item'] ?? 0);
        $this->view->assign('uid', $uid);

        $pageUid = (int) $this->request->getAttribute('routing')->getPageId();
        $currentItem = $this->postRepository->findByUid($uid);
        $currentItemCategories = [];
        if ($currentItem) {
            foreach ($currentItem->getCategory() as $cat) {
                $currentItemCategories[] = (int) $cat->getUid();
            }
        }

        $categories = $this->categoryRepository->getCategoryTree();
        $site = $this->request->getAttribute('site');
        $siteSettings = $site->getSettings();
        $detailPid = (int) ($siteSettings->get('insightsDetailPid') ?? $settings['detailPid'] ?? $pageUid);
        $categorieswithURl = $this->mapCategoriesWithPosts($categories, $detailPid, $uid, $currentItemCategories);

        $this->view->assign('categories', $categorieswithURl);
        return $this->htmlResponse();
    }

    protected function mapCategoriesWithPosts(array $categories, int $detailPid, int $currentPostUid, array $currentItemCategories): array
    {
        $mapped = [];
        foreach ($categories as $cat) {
            $catUid = (int) $cat->getUid();

            // Fetch all posts for this category
            $this->postRepository->setStorage();
            $postsInCategory = $this->postRepository->findByCategory($cat);
            $mappedPosts = [];
            foreach ($postsInCategory as $post) {
                $mappedPosts[] = [
                    'uid' => $post->getUid(),
                    'title' => $post->getTitle(),
                    'url' => $this->uriBuilder->reset()
                        ->setTargetPageUid($detailPid)
                        ->setArguments([
                            'tx_insights_postdetail' => [
                                'item' => $post->getUid(),
                                'category' => $catUid
                            ]
                        ])
                        ->build(),
                    'active' => ((int) $post->getUid() === (int) $currentPostUid)
                ];
            }

            // Map children recursively
            $childs = [];
            if (!empty($cat->childs)) {
                $childs = $this->mapCategoriesWithPosts($cat->childs, $detailPid, $currentPostUid, $currentItemCategories);
            }

            // Determine if active (if category itself is active or any child/post is active)
            $isActive = in_array($catUid, $currentItemCategories);

            // Use the provided detailPid or a fallback for category links if no specific listPid exists
            $categoryListPid = (int) ($this->request->getAttribute('site')->getSettings()->get('insightsCategoryPid') ?? $this->settings['categoryListPid'] ?? $this->settings['listPid'] ?? 0);
            $mainUrl = $this->uriBuilder->reset()
                ->setTargetPageUid($categoryListPid)
                ->setArguments([
                    'tx_insights_categoryinsights' => [
                        'category' => $catUid
                    ]
                ])
                ->build();

            $mainItemId = 0;
            if (!empty($mappedPosts)) {
                $mainItemId = $mappedPosts[0]['uid'];
            } elseif (!empty($childs)) {
                $mainItemId = $childs[0]['itemId'];
            }

            $mapped[] = [
                'uid' => $catUid,
                'title' => $cat->getTitle(),
                'description' => $cat->getDescription(),
                'url' => $mainUrl,
                'itemId' => $mainItemId,
                'active' => $isActive,
                'childs' => $childs,
                'posts' => $mappedPosts
            ];
        }
        return $mapped;
    }
    public function insightelementsAction(): ResponseInterface
    {
        $settings = $this->settings;
        $this->view->assign('settings', $settings);
        $_params = $this->request->getQueryParams();
        if (isset($_params['tx_insights_postdetail']['item']) && $_params['tx_insights_postdetail']['item'] != "") {
            $uid = $_params['tx_insights_postdetail']['item'];
        } else {
            $uid = 0;
        }
        if ($uid != "") {
            $item = null;
            $item = $this->postRepository->findByUid((int) $uid);
            if ($item) {
                $this->view->assign('item', $item);
                if ($item->getCelementIdList()) {
                    $elementIds = explode(",", $item->getCelementIdList());
                    $ceHeaders = [];
                    foreach ($elementIds as $uid) {
                        $record = BackendUtility::getRecord('tt_content', $uid, '*');
                        if (isset($record['header']) && $record['header'] != "") {
                            $ceHeaders[] = $record;
                        }
                    }
                    $this->view->assign('ceHeaders', $ceHeaders);
                }
            }
        }
        $site = $GLOBALS['TYPO3_REQUEST']->getAttribute('site');
        $homePageId = $site->getRootPageId();
        $this->view->assign('homePageId', $homePageId);

        return $this->htmlResponse();
    }

    public function searchpopularAction(): ResponseInterface
    {
        $settings = $this->settings;
        $this->view->assign('settings', $settings);
        return $this->htmlResponse();
    }


}
