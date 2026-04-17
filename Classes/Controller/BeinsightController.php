<?php
declare(strict_types=1);

namespace T3element\Insights\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Http\HtmlResponse;
use T3element\Insights\Domain\Repository\CategoryRepository;
use T3element\Insights\Domain\Repository\AuthorRepository;
use T3element\Insights\Domain\Repository\TagsRepository;
use T3element\Insights\Domain\Repository\PostRepository;
use T3element\Insights\Domain\Repository\LikesRepository;
use T3element\Insights\Domain\Repository\CommentsRepository;
use T3element\Insights\Domain\Repository\ConfigRepository;
use T3element\Insights\Domain\Repository\ViewersRepository;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Core\Context\Context;
use T3element\Insights\Domain\Model\Config;

class BeinsightController extends ActionController
{
    protected $authorRepository = null;
    public function injectAuthorRepository(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    protected $viewersRepository = null;
    public function injectViewersRepository(ViewersRepository $viewersRepository)
    {
        $this->viewersRepository = $viewersRepository;
    }

    protected $tagsRepository = null;
    public function injectTagsRepository(TagsRepository $tagsRepository)
    {
        $this->tagsRepository = $tagsRepository;
    }

    protected $postRepository = null;
    public function injectPostRepository(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    protected $likesRepository = null;
    public function injectLikesRepository(LikesRepository $likesRepository)
    {
        $this->likesRepository = $likesRepository;
    }

    protected $ConfigRepository = null;
    public function injectConfigRepository(ConfigRepository $ConfigRepository)
    {
        $this->ConfigRepository = $ConfigRepository;
    }

    protected $commentsRepository = null;
    public function injectCommentsRepository(CommentsRepository $commentsRepository)
    {
        $this->commentsRepository = $commentsRepository;
    }

    protected $categoryRepository = null;
    public function injectCategoryRepository(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    protected PageRenderer $pageRenderer;
    protected PersistenceManager $persistenceManager;
    protected Context $context;

    public function injectPersistenceManager(PersistenceManager $persistenceManager)
    {
        $this->persistenceManager = $persistenceManager;
    }

    public function __construct(
        protected readonly ModuleTemplateFactory $moduleTemplateFactory,
        PageRenderer $pageRenderer,
        Context $context,
    ) {
        $this->pageRenderer = $pageRenderer;
        $this->context = $context;
    }

    public function benewslistAction()
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $this->authorRepository->setStorage();
        $author_items = $this->authorRepository->findAll();
        $authors = [];
        if ($author_items) {
            foreach ($author_items as $item) {
                $tempaut['uid'] = $item->getUid();
                $tempaut['name'] = $item->getName();
                $authors[] = $tempaut;
            }
        }
        $moduleTemplate->assign('authors', $authors);

        $this->tagsRepository->setStorage();
        $tagitems = $this->tagsRepository->findAll();
        $tags = [];
        if ($tagitems) {
            foreach ($tagitems as $item) {
                $ttmp['uid'] = $item->getUid();
                $ttmp['title'] = $item->getTitle();
                $tags[] = $ttmp;
            }
        }
        $moduleTemplate->assign('tags', $tags);

        $this->categoryRepository->setStorage();
        $categories = $this->categoryRepository->findAll();
        $catss = [];
        if ($categories) {
            foreach ($categories as $item) {
                $cssts['uid'] = $item->getUid();
                $cssts['title'] = $item->getTitle();
                $catss[] = $cssts;
            }
        }
        $moduleTemplate->assign('category', $catss);

        $moduleTemplate->setTitle('News List');
        $this->pageRenderer->addCssFile('EXT:insights/Resources/Public/css/beModuleStyle.css');
        $this->pageRenderer->addCssFile('EXT:insights/Resources/Public/css/bemoduleIcons.css');
        $this->pageRenderer->addCssFile('EXT:insights/Resources/Public/css/beModuleFontsStyle.css');
        $this->pageRenderer->addCssFile('EXT:insights/Resources/Public/css/datatables.min.css');
        $this->pageRenderer->addCssFile('EXT:insights/Resources/Public/css/select2.min.css');
        $this->pageRenderer->addJsFile('EXT:insights/Resources/Public/js/datatables.min.js');
        $this->pageRenderer->addJsFile('EXT:insights/Resources/Public/js/bejs.js');
        $this->pageRenderer->addJsFile('EXT:insights/Resources/Public/js/select2.min.js');

        return new HtmlResponse(
            $moduleTemplate->render('Postlist')
        );
    }

    public function becommentsAction()
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->setTitle('Comments');
        $this->postRepository->setStorage();
        $postes = $this->postRepository->findAll();
        $postts = [];
        if ($postes) {
            foreach ($postes as $item) {
                $pptmp['uid'] = $item->getUid();
                $pptmp['title'] = $item->getTitle();
                $postts[] = $pptmp;
            }
        }
        $moduleTemplate->assign('posts', $postts);
        $this->pageRenderer->addCssFile('EXT:insights/Resources/Public/css/beModuleStyle.css');
        $this->pageRenderer->addCssFile('EXT:insights/Resources/Public/css/bemoduleIcons.css');
        $this->pageRenderer->addCssFile('EXT:insights/Resources/Public/css/beModuleFontsStyle.css');
        $this->pageRenderer->addCssFile('EXT:insights/Resources/Public/css/datatables.min.css');
        $this->pageRenderer->addCssFile('EXT:insights/Resources/Public/css/select2.min.css');
        $this->pageRenderer->addJsFile('EXT:insights/Resources/Public/js/datatables.min.js');
        $this->pageRenderer->addJsFile('EXT:insights/Resources/Public/js/bejs.js');
        $this->pageRenderer->addJsFile('EXT:insights/Resources/Public/js/select2.min.js');

        return new HtmlResponse(
            $moduleTemplate->render('Comments')
        );
    }

    public function betagsAction()
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->setTitle('Tags List');

        $backendUriBuilder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Backend\Routing\UriBuilder::class
        );

        $this->tagsRepository->setStorage();
        $tags_items = $this->tagsRepository->findAll();

        $returnUrl = $backendUriBuilder->buildUriFromRoute(
            'tx_insights_tags'
        );
        if ($tags_items) {
            $lst = 0;
            foreach ($tags_items as $item) {
                $editLink = (string) $backendUriBuilder->buildUriFromRoute(
                    'record_edit',
                    [
                        'edit' => [
                            'tx_insights_domain_model_tags' => [
                                $item->getUid() => 'edit'
                            ]
                        ],
                        'returnUrl' => $returnUrl
                    ]
                );
                $moduleTemplate->assign('edit_tag_' . $lst, $editLink);
                $lst++;
            }
            $moduleTemplate->assign('tags_items', $tags_items);
        } else {
            $moduleTemplate->assign('tags_items', $tags_items);
        }
        $this->pageRenderer->addCssFile('EXT:insights/Resources/Public/css/beModuleStyle.css');
        $this->pageRenderer->addCssFile('EXT:insights/Resources/Public/css/bemoduleIcons.css');
        $this->pageRenderer->addCssFile('EXT:insights/Resources/Public/css/beModuleFontsStyle.css');
        $this->pageRenderer->addCssFile('EXT:insights/Resources/Public/css/datatables.min.css');
        $this->pageRenderer->addJsFile('EXT:insights/Resources/Public/js/datatables.min.js');
        return new HtmlResponse(
            $moduleTemplate->render('Tags')
        );
    }

    public function beauthorsAction()
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->setTitle('Authors');
        $this->pageRenderer->addCssFile('EXT:insights/Resources/Public/css/beModuleStyle.css');
        $this->pageRenderer->addCssFile('EXT:insights/Resources/Public/css/bemoduleIcons.css');
        $this->pageRenderer->addCssFile('EXT:insights/Resources/Public/css/beModuleFontsStyle.css');
        $this->pageRenderer->addCssFile('EXT:insights/Resources/Public/css/datatables.min.css');
        $this->pageRenderer->addJsFile('EXT:insights/Resources/Public/js/datatables.min.js');
        $this->pageRenderer->addJsFile('EXT:insights/Resources/Public/js/bejs.js');
        return new HtmlResponse(
            $moduleTemplate->render('Authors')
        );
    }

    public function benewsaddAction(): ResponseInterface
    {
        $table = 'tx_insights_domain_model_post';
        /** @var \T3element\Insights\Domain\Model\Config|null $config */
        $config = $this->ConfigRepository->findByUid(1);

        if ($config && $config->getStorageid() > 0) {
            $pid = $config->getStorageid();
        } else {
            $pid = 0;
            $backendUriBuilder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
                \TYPO3\CMS\Backend\Routing\UriBuilder::class
            );
            $configuri = $backendUriBuilder->buildUriFromRoute(
                'tx_insights_config',
                [
                    'message' => 'Please select folders for store Insights!'
                ]
            );
            return $this->redirectToUri($configuri);
        }
        $backendUriBuilder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Backend\Routing\UriBuilder::class
        );

        $returnUrl = $backendUriBuilder->buildUriFromRoute(
            'tx_insights_newslist'
        );

        $uri = (string) $backendUriBuilder->buildUriFromRoute(
            'record_edit',
            [
                'edit' => [
                    $table => [
                        $pid => 'new'
                    ]
                ],
                'returnUrl' => $returnUrl
            ]
        );
        return $this->redirectToUri($uri);

    }

    public function updateStatusAction(): ResponseInterface
    {
        $request = $GLOBALS['TYPO3_REQUEST'];
        $queryParams = $request->getQueryParams();
        $uid = (int) ($queryParams['uid'] ?? null);
        $poststatus = (int) ($queryParams['poststatus'] ?? null);
        $_status = $poststatus ? 0 : 1;

        if ($uid > 0) {
            $item = $this->postRepository->findByUid($uid);
            if ($item) {
                $item->setPoststatus($_status);
                $this->postRepository->update($item);
                $this->persistenceManager->persistAll();
            }
        }
        return new JsonResponse([
            "msg" => "Status Updated !!"
        ]);
    }

    public function commentStatus(): ResponseInterface
    {
        $request = $GLOBALS['TYPO3_REQUEST'];
        $queryParams = $request->getQueryParams();
        $uid = (int) ($queryParams['uid'] ?? null);
        $status = ($queryParams['status'] ?? null);
        $_status = "pending";
        if ($status == "pending") {
            $_status = "approved";
        }

        if ($uid > 0) {
            $item = $this->commentsRepository->findByUid($uid);
            if ($item) {
                $item->setStatus($_status);
                $this->commentsRepository->update($item);
                $this->persistenceManager->persistAll();
            }
        }
        return new JsonResponse([
            "msg" => "Status Updated !!"
        ]);
    }

    public function beconfigAction()
    {
        /** @var \T3element\Insights\Domain\Model\Config|null $config */
        $config = $this->ConfigRepository->findByUid(1);
        if (isset($_GET['message'])) {
            echo '<p class="alert alert-danger m-3">' . htmlspecialchars($_GET['message']) . '</p>';
        }
        $filePath = ExtensionManagementUtility::extPath('insights') . 'Configuration/AI/Models.php';
        $aiModels = file_exists($filePath) ? require($filePath) : [];
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->assign('aiModels', $aiModels);

        $moduleTemplate->setTitle('Config for Insights');
        if ($config) {
            if ($config->getSocialmedia()) {
                $moduleTemplate->assign('socialmedia', json_decode($config->getSocialmedia()));
            }
            if ($config->getAioptions()) {
                // Decode the first layer
                $savedAiSettings = json_decode($config->getAioptions(), true);

                // If it's still a string after the first decode, decode it again
                if (is_string($savedAiSettings)) {
                    $savedAiSettings = json_decode($savedAiSettings, true);
                }

                foreach ($aiModels as $key => &$modelData) {
                    $modelData['key'] = $key;
                    foreach ($savedAiSettings as $savedItem) {
                        if ($savedItem['agent'] === $key) {
                            $modelData['apikey'] = $savedItem['apikey'] ?? '';
                            $modelData['active'] = $savedItem['active'] ?? 0;
                        }
                    }
                }
                $moduleTemplate->assign('aiModels', $aiModels);
            }
            $moduleTemplate->assign('status_to_show', $config->getStatusToShow());
            $moduleTemplate->assign('storageid', $config->getStorageid());
            $moduleTemplate->assign('displaycomment', $config->getDisplaycomment());
            $moduleTemplate->assign('nextprvbtn', $config->getNextprvbtn());
            $moduleTemplate->assign('socialshare', $config->getSocialshare());
            $moduleTemplate->assign('addcomments', $config->getAddcomments());
            $moduleTemplate->assign('displayauthor', $config->getDisplayauthor());
            $moduleTemplate->assign('langwisecomment', $config->getLangwisecomment());

            if ($config->getTemplates()) {
                $templateArray = json_decode($config->getTemplates(), true);
                $moduleTemplate->assign('templates', $templateArray);
            } else {
                $templates = $this->getTemplateFiles();
                $moduleTemplate->assign('templates', $templates);
            }
        } else {
            $templates = $this->getTemplateFiles();
            $moduleTemplate->assign('templates', $templates);
        }
        $folders = $this->getFolders();
        $moduleTemplate->assign('folders', $folders);

        $this->pageRenderer->addCssFile('EXT:insights/Resources/Public/css/beModuleStyle.css');
        $this->pageRenderer->addCssFile('EXT:insights/Resources/Public/css/bemoduleIcons.css');
        $this->pageRenderer->addCssFile('EXT:insights/Resources/Public/css/beModuleFontsStyle.css');
        $this->pageRenderer->addCssFile('EXT:insights/Resources/Public/css/select2.min.css');
        $this->pageRenderer->addJsFile('EXT:insights/Resources/Public/js/jquery-3.7.1.min.js');
        $this->pageRenderer->addJsFile('EXT:insights/Resources/Public/js/beconfig.js');
        $this->pageRenderer->addCssFile('EXT:insights/Resources/Public/css/datatables.min.css');
        $this->pageRenderer->addJsFile('EXT:insights/Resources/Public/js/datatables.min.js');
        $this->pageRenderer->addJsFile('EXT:insights/Resources/Public/js/bootstrap.min.js');
        $this->pageRenderer->addJsFile('EXT:insights/Resources/Public/js/select2.min.js');

        $this->pageRenderer->addCssFile('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/codemirror.min.css');
        $this->pageRenderer->addCssFile('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/theme/dracula.min.css');
        $this->pageRenderer->addCssFile('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/addon/hint/show-hint.min.css');
        $this->pageRenderer->addJsFile('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/codemirror.min.js');
        $this->pageRenderer->addJsFile('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/mode/htmlmixed/htmlmixed.min.js');
        $this->pageRenderer->addJsFile('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/mode/xml/xml.min.js');
        $this->pageRenderer->addJsFile('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/mode/javascript/javascript.min.js');
        $this->pageRenderer->addJsFile('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/mode/css/css.min.js');
        $this->pageRenderer->addJsFile('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/addon/hint/show-hint.min.js');
        $this->pageRenderer->addJsFile('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/addon/hint/html-hint.min.js');
        $this->pageRenderer->addJsFile('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/addon/hint/javascript-hint.min.js');
        $this->pageRenderer->addJsFile('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/addon/hint/css-hint.min.js');
        $this->pageRenderer->addJsFile('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/addon/edit/closebrackets.min.js');
        $this->pageRenderer->addJsFile('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/addon/edit/matchbrackets.min.js');
        $this->pageRenderer->addJsFile('EXT:insights/Resources/Public/js/editor.js');

        return new HtmlResponse(
            $moduleTemplate->render('Beconfig')
        );
    }

    public function saveconfigAction(): ResponseInterface
    {
        $arguments = $this->request->getArguments();
        $socialmedia = $arguments['settingsform']['socialmedia'];
        $_files = $files = $this->request->getUploadedFiles();
        $files = null;
        if ($_files) {
            $files = $_files['settingsform']['socialmedia'];
        }

        /** @var \T3element\Insights\Domain\Model\Config|null $config */
        $config = $this->ConfigRepository->findByUid(1);
        if (!$config) {
            $config = new \T3element\Insights\Domain\Model\Config();
            $this->ConfigRepository->add($config);
            $this->persistenceManager->persistAll();
        }

        $socialmedia = $arguments['settingsform']['socialmedia'] ?? [];
        $aiconfig = $arguments['aiConfig'];

        $socials = [];
        foreach ($socialmedia as $index => $item) {
            $link = $item['link'] ?? '';
            $fileObject = $files[$index]['icon'] ?? null;
            $icon = null;

            if ($fileObject instanceof \TYPO3\CMS\Core\Http\UploadedFile) {
                $targetFolder = GeneralUtility::getFileAbsFileName('fileadmin/social_icons/');
                if (!is_dir($targetFolder))
                    mkdir($targetFolder, 0775, true);
                $filename = uniqid() . '-' . $fileObject->getClientFilename();
                $fileObject->moveTo($targetFolder . $filename);
                $icon = '/fileadmin/social_icons/' . $filename;
            } elseif ($config->getUid()) {
                $oldsocial = json_decode($config->getSocialmedia(), true) ?? [];
                $icon = $oldsocial[$index]['icon'] ?? null;
            }

            if (empty($link) && empty($icon))
                continue;

            $socials[$index] = ['link' => $link, 'icon' => $icon];
        }

        $config->setSocialmedia(!empty($socials) ? json_encode($socials) : $config->getSocialmedia());

        $config->setAioptions(json_encode($aiconfig));
        if (isset($arguments['status_to_show'])) {
            $config->setStatusToShow((int) $arguments['status_to_show']);
        } elseif (!$config->getUid()) {
            $config->setStatusToShow(0);
        } else {
            $config->setStatusToShow(0);
        }

        if (isset($arguments['storageid'])) {
            $config->setStorageid((int) $arguments['storageid']);
        } elseif (!$config->getUid()) {
            $config->setStorageid(0);
        } else {
            $config->setStorageid(0);

        }

        $booleanFields = [
            'displaycomment',
            'nextprvbtn',
            'socialshare',
            'addcomments',
            'displayauthor',
            'langwisecomment'
        ];

        foreach ($booleanFields as $field) {
            if (isset($arguments[$field])) {
                $value = ((int) $arguments[$field] === 1) ? 1 : 0;
            } else {
                $value = 0;
            }

            $setter = 'set' . ucfirst($field);
            $config->$setter($value);
        }

        if ($config->getUid()) {
            $this->ConfigRepository->update($config);
        } else {
            $this->ConfigRepository->add($config);
        }

        $this->persistenceManager->persistAll();

        $backendUriBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Backend\Routing\UriBuilder::class);
        $url = $backendUriBuilder->buildUriFromRoute('tx_insights_config');
        return $this->redirectToUri($url);
    }

    public function savetemplateAction(): ResponseInterface
    {
        $arguments = $this->request->getArguments();
        $templates = $arguments['templates'];

        $config = $this->ConfigRepository->findByUid(1);

        if ($templates) {
            if ($config) {
                $config->setTemplates(json_encode($templates));
                $this->ConfigRepository->update($config);
            } else {
                $_newobj = new Config();
                $_newobj->setTemplates(json_encode($templates));
                $this->ConfigRepository->add($_newobj);
                $config = $this->ConfigRepository->findByUid(1);
            }

            $extpath = GeneralUtility::makeInstance(ExtensionManagementUtility::class)::extPath('insights');
            $templateMapping = [
                'Postlist' => "Resources/Private/Templates/Insights/Postlist.html",
                'Postdetail' => "Resources/Private/Templates/Insights/Postdetail.html",
                'Commentform' => "Resources/Private/Partials/Insights/Commentform.html",
                'Tags' => "Resources/Private/Templates/Insights/Tags.html",
                'Postfilter' => "Resources/Private/Templates/Insights/Postfilter.html",
                'Filterresultlist' => "Resources/Private/Templates/Insights/Filterresultlist.html",
                'Breadcrumbs' => "Resources/Private/Templates/Insights/Breadcrumbs.html",
                'Insightelements' => "Resources/Private/Templates/Insights/Insightelements.html",
                'Searchpopular' => "Resources/Private/Templates/Insights/Searchpopular.html",
                'Quicksearch' => "Resources/Private/Templates/Insights/Quicksearch.html",
                'Recommended' => "Resources/Private/Templates/Insights/Recommended.html",
                'Categoryinsights' => "Resources/Private/Templates/Insights/Categoryinsights.html",
            ];

            foreach ($templateMapping as $key => $relativePath) {
                if (isset($templates[$key])) {
                    $absolutePath = $extpath . $relativePath;
                    if ($ttcont_dat = fopen($absolutePath, "w")) {
                        if (fwrite($ttcont_dat, $templates[$key])) {
                            fclose($ttcont_dat);
                        }
                    }
                }
            }
        }
        $backendUriBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Backend\Routing\UriBuilder::class);
        $url = $backendUriBuilder->buildUriFromRoute('tx_insights_config');
        return $this->redirectToUri($url);
    }

    public function bePostData(): ResponseInterface
    {
        $request = $GLOBALS['TYPO3_REQUEST'];
        $queryParams = $request->getQueryParams();
        $uid = (int) ($queryParams['uid'] ?? null);
        if ($uid > 0) {
            $item = $this->postRepository->findByUid($uid);
            if ($item) {
                $categories = [];

                if ($item->getCategory() && count($item->getCategory())) {
                    foreach ($item->getCategory() as $cat) {
                        $categories[] = $cat->getTitle();
                    }
                }

                $author = $item->getAuthor();
                $_author = "";
                if ($author != "") {
                    $authorData = null;
                    $authorData = $this->authorRepository->findByUid((int) $author);
                    if ($authorData) {
                        $_author = $authorData->getName();
                    }
                }
                $_tags = $item->getTags();
                $_tagsData = [];
                foreach ($_tags as $tuid) {
                    $_result = $this->tagsRepository->findByUid((int) $tuid->getUid());
                    if ($_result) {
                        $_tagsData[] = $_result->getTitle();
                    }
                }

                $thumbnails = $item->getThumbnail();
                $_thumbnails = "";
                if (count($thumbnails)) {
                    foreach ($thumbnails as $thumbnail) {
                        $_thumbnails = $thumbnail->getOriginalResource()->getPublicUrl();
                    }
                }
                $likeinfo = $this->getCurrentLikestatus($uid);
                $likkes = [];
                if (isset($likeinfo['likedata'])) {
                    foreach ($likeinfo['likedata'] as $lk) {
                        $_tmp['uid'] = $lk->getUid();
                        $_tmp['ipaddress'] = $lk->getIpaddress();
                        $_tmp['browser'] = $lk->getBrowser();
                        $likkes[] = $_tmp;
                    }
                } else {
                    $likkes = [];
                }
                return new JsonResponse([
                    'data' => [
                        'uid' => $item->getUid(),
                        'title' => $item->getTitle(),
                        'author' => $_author,
                        'thumbnail' => $_thumbnails,
                        'slug' => $item->getSlug(),
                        'alternativetitle' => $item->getAlternativetitle(),
                        'metakeyword' => $item->getMetakeyword(),
                        'metadescription' => $item->getMetadescription(),
                        'teaser' => $item->getTeaser(),
                        'description' => $item->getDescription(),
                        'publishdate' => $item->getPublishdate()?->format('d-m-Y'),
                        "archivedate" => $item->getArchivedate()?->format('d-m-Y'),
                        'category' => implode(",", $categories),
                        'viewers' => $item->getViewers(),
                        'tags' => implode(",", $_tagsData),
                        'poststatus' => $item->getPoststatus(),
                        'likes' => $likeinfo['likes'],
                        'dislikes' => $likeinfo['dislikes'],
                        'likedata' => $likkes,
                    ]
                ]);
            }
        }
        return new JsonResponse(['error' => ""]);
    }

    public function postsbyAjax(): ResponseInterface
    {
        $request = $GLOBALS['TYPO3_REQUEST'];
        $queryParams = $request->getQueryParams();

        $publishDate = $queryParams['columns'][0]['search']['value'] ?? '';
        $authorfilter = $queryParams['columns'][3]['search']['value'] ?? '';
        $catfilter = (int) ($queryParams['columns'][2]['search']['value'] ?? 0);
        $tagfilter = $queryParams['columns'][4]['search']['value'] ?? '';

        if (!empty($publishDate)) {
            $startOfDay = strtotime($publishDate . ' 00:00:00');
            $endOfDay = strtotime($publishDate . ' 23:59:59');
        }

        $draw = (int) ($queryParams['draw'] ?? 1);
        $start = (int) ($queryParams['start'] ?? 0);
        $length = (int) ($queryParams['length'] ?? 10);
        $search = $queryParams['search']['value'] ?? '';
        $orderColumnIndex = (int) ($queryParams['order'][0]['column'] ?? 0);
        $orderDir = $queryParams['order'][0]['dir'] ?? 'desc';
        $columns = $queryParams['columns'] ?? [];

        $orderField = $columns[$orderColumnIndex]['data'] ?? 'uid';

        $allowedOrderFields = [
            'uid',
            'title',
            'publishdate',
            'archivedate',
            'viewers',
            'poststatus'
        ];

        if (!in_array($orderField, $allowedOrderFields, true)) {
            $orderField = 'uid';
        }

        $qb = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_insights_domain_model_post');

        $qb->select('*')
            ->from('tx_insights_domain_model_post');

        $qb->orderBy(
            $orderField,
            $orderDir === 'desc' ? 'DESC' : 'ASC'
        )->setFirstResult($start)
            ->setMaxResults($length);

        if ($search !== '') {
            $qb->andWhere(
                $qb->expr()->or(
                    $qb->expr()->like('title', $qb->createNamedParameter('%' . $search . '%')),
                    $qb->expr()->like('teaser', $qb->createNamedParameter('%' . $search . '%')),
                    $qb->expr()->like('description', $qb->createNamedParameter('%' . $search . '%'))
                )
            );
        }

        if (!empty($publishDate)) {
            $qb->andWhere(
                $qb->expr()->gte(
                    'publishdate',
                    $qb->createNamedParameter($startOfDay, Connection::PARAM_INT)
                ),
                $qb->expr()->lte(
                    'publishdate',
                    $qb->createNamedParameter($endOfDay, Connection::PARAM_INT)
                )
            );
        }
        if (!empty($authorfilter)) {
            $qb->andWhere(
                $qb->expr()->eq(
                    'author',
                    $qb->createNamedParameter($authorfilter, Connection::PARAM_INT)
                )
            );
        }

        if (!empty($tagfilter)) {
            $tagUids = (int) $tagfilter;
            if ($tagUids) {
                $joinCondition = 'tag_mm.uid_foreign = ' . $tagUids .
                    ' AND tag_mm.uid_local = ' . $qb->quoteIdentifier('tx_insights_domain_model_post.uid') . '';
                $qb->innerJoin(
                    'tx_insights_domain_model_post',
                    'tx_insights_post_tag_mm',
                    'tag_mm',
                    $joinCondition
                );
            }
        }

        if ($catfilter > 0) {
            $joinCondition = $qb->expr()->and(
                $qb->expr()->eq('mm.uid_foreign', $qb->quoteIdentifier('tx_insights_domain_model_post.uid')),
                $qb->expr()->eq('mm.tablenames', $qb->createNamedParameter('tx_insights_domain_model_post')),
                $qb->expr()->eq('mm.uid_local', $qb->createNamedParameter($catfilter, \Doctrine\DBAL\ParameterType::INTEGER))
            );
            $qb->innerJoin(
                'tx_insights_domain_model_post',
                'sys_category_record_mm',
                'mm',
                (string) $joinCondition
            );
        }

        $rows = $qb->executeQuery()->fetchAllAssociative();

        $totalQb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_insights_domain_model_post');
        $total = (int) $totalQb->count('uid')->from('tx_insights_domain_model_post')->executeQuery()->fetchOne();

        $filteredQb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_insights_domain_model_post');
        $filteredQb->count('uid')->from('tx_insights_domain_model_post');

        if ($search !== '') {
            $filteredQb->andWhere(
                $filteredQb->expr()->or(
                    $filteredQb->expr()->like('title', $filteredQb->createNamedParameter('%' . $search . '%')),
                    $filteredQb->expr()->like('teaser', $filteredQb->createNamedParameter('%' . $search . '%')),
                    $filteredQb->expr()->like('description', $filteredQb->createNamedParameter('%' . $search . '%'))
                )
            );
        }

        if (!empty($publishDate)) {
            $filteredQb->andWhere(
                $filteredQb->expr()->gte('publishdate', $filteredQb->createNamedParameter($startOfDay, \Doctrine\DBAL\ParameterType::INTEGER)),
                $filteredQb->expr()->lte('publishdate', $filteredQb->createNamedParameter($endOfDay, \Doctrine\DBAL\ParameterType::INTEGER))
            );
        }

        if (!empty($authorfilter)) {
            $filteredQb->andWhere($filteredQb->expr()->eq('author', $filteredQb->createNamedParameter($authorfilter, Connection::PARAM_INT)));
        }
        if (!empty($tagfilter)) {
            $tagUids = (int) $tagfilter;
            if ($tagUids) {
                $joinCondition = 'tag_mm.uid_foreign = ' . $tagUids . ' AND tag_mm.uid_local = ' . $filteredQb->quoteIdentifier('tx_insights_domain_model_post.uid') . '';
                $filteredQb->innerJoin('tx_insights_domain_model_post', 'tx_insights_post_tag_mm', 'tag_mm', $joinCondition);
            }
        }
        if ($catfilter > 0) {
            $joinCondition = $filteredQb->expr()->and(
                $filteredQb->expr()->eq('mm.uid_foreign', $filteredQb->quoteIdentifier('tx_insights_domain_model_post.uid')),
                $filteredQb->expr()->eq('mm.tablenames', $filteredQb->createNamedParameter('tx_insights_domain_model_post')),
                $filteredQb->expr()->eq('mm.uid_local', $filteredQb->createNamedParameter($catfilter, \Doctrine\DBAL\ParameterType::INTEGER))
            );
            $filteredQb->innerJoin('tx_insights_domain_model_post', 'sys_category_record_mm', 'mm', (string) $joinCondition);
        }
        $recordsFiltered = (int) $filteredQb->executeQuery()->fetchOne();

        $aimagePath = "EXT:insights/Resources/Public/Icons/blank.png";
        /** @extensionScannerIgnoreLine */
        $imagePath = PathUtility::getPublicResourceWebPath($aimagePath);

        return new JsonResponse([
            'draw' => $draw,
            'blankImage' => $imagePath,
            'recordsTotal' => (int) $total,
            'recordsFiltered' => (int) $recordsFiltered,
            'data' => array_map(
                function ($row) {
                    $backendUriBuilder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Backend\Routing\UriBuilder::class);
                    $_row = $this->postRepository->findByUid($row['uid']);
                    $categories = [];
                    if ($_row->getCategory() && count($_row->getCategory())) {
                        foreach ($_row->getCategory() as $cat) {
                            $categories[] = $cat->getTitle();
                        }
                    }
                    $author = $_row->getAuthor();
                    $_author = "";
                    if ($author != "") {
                        $authorData = $this->authorRepository->findByUid((int) $author);
                        if ($authorData) {
                            $_author = $authorData->getName();
                        }
                    }
                    $_tags = $_row->getTags();
                    $_tagsData = [];
                    if (count($_tags)) {
                        foreach ($_tags as $tag) {
                            $_tagsData[] = $tag->getTitle();
                        }
                    }
                    $thumbnails = $_row->getThumbnail();
                    $_thumbnails = "";
                    if (count($thumbnails)) {
                        foreach ($thumbnails as $thumbnail) {
                            $_thumbnails = $thumbnail->getOriginalResource()->getPublicUrl();
                        }
                    }
                    $returnUrl = $backendUriBuilder->buildUriFromRoute('tx_insights_newslist');
                    $editLink = (string) $backendUriBuilder->buildUriFromRoute(
                        'record_edit',
                        [
                            'edit' => [
                                'tx_insights_domain_model_post' => [
                                    $row['uid'] => 'edit'
                                ]
                            ],
                            'returnUrl' => $returnUrl
                        ]
                    );
                    $likeinfo = $this->getCurrentLikestatus($row['uid']);
                    return [
                        'uid' => $row['uid'],
                        'title' => $row['title'],
                        'author' => $_author,
                        'thumbnail' => $_thumbnails,
                        'slug' => $row['slug'],
                        'alternativetitle' => $row['alternativetitle'],
                        'metakeyword' => $row['metakeyword'],
                        'metadescription' => $row['metadescription'],
                        'teaser' => $row['teaser'],
                        'description' => $row['description'],
                        'publishdate' => $_row->getPublishdate()?->format('d-m-Y'),
                        "archivedate" => $_row->getArchivedate()?->format('d-m-Y'),
                        'category' => $categories,
                        'viewers' => $row['viewers'],
                        'tags' => $_tagsData,
                        'poststatus' => $row['poststatus'],
                        'editlink' => $editLink,
                        'likes' => $likeinfo['likes'],
                    ];
                },
                $rows
            ),
        ]);
    }

    public function commentsbyAjax(): ResponseInterface
    {
        $request = $GLOBALS['TYPO3_REQUEST'];
        $queryParams = $request->getQueryParams();

        $draw = (int) ($queryParams['draw'] ?? 1);
        $start = (int) ($queryParams['start'] ?? 0);
        $length = (int) ($queryParams['length'] ?? 10);
        $search = $queryParams['search']['value'] ?? '';
        $orderColumnIndex = (int) ($queryParams['order'][0]['column'] ?? 0);
        $orderDir = $queryParams['order'][0]['dir'] ?? 'desc';
        $columns = $queryParams['columns'] ?? [];
        $crdatet = $queryParams['columns'][0]['search']['value'] ?? '';
        $postfilter = $queryParams['columns'][3]['search']['value'] ?? '';

        $orderField = $columns[$orderColumnIndex]['data'] ?? 'uid';
        $allowedOrderFields = ['uid', 'name', 'comment', 'post', 'status'];

        if (!empty($crdatet)) {
            $startOfDay = strtotime($crdatet . ' 00:00:00');
            $endOfDay = strtotime($crdatet . ' 23:59:59');
        }

        if (!in_array($orderField, $allowedOrderFields, true)) {
            $orderField = 'uid';
        }

        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_insights_domain_model_comments');
        $qb->select('*')->from('tx_insights_domain_model_comments');
        $qb->orderBy($orderField, $orderDir === 'desc' ? 'DESC' : 'ASC')->setFirstResult($start)->setMaxResults($length);

        if ($search !== '') {
            $qb->andWhere($qb->expr()->or(
                $qb->expr()->like('name', $qb->createNamedParameter('%' . $search . '%')),
                $qb->expr()->like('email', $qb->createNamedParameter('%' . $search . '%')),
                $qb->expr()->like('comment', $qb->createNamedParameter('%' . $search . '%'))
            ));
        }
        if (!empty($crdatet)) {
            $qb->andWhere($qb->expr()->gte('crdate', $qb->createNamedParameter($startOfDay, Connection::PARAM_INT)), $qb->expr()->lte('crdate', $qb->createNamedParameter($endOfDay, Connection::PARAM_INT)));
        }
        if (!empty($postfilter)) {
            $qb->andWhere($qb->expr()->eq('post', $qb->createNamedParameter($postfilter, Connection::PARAM_INT)));
        }

        $rows = $qb->executeQuery()->fetchAllAssociative();
        $totalQb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_insights_domain_model_comments');
        $total = (int) $totalQb->count('uid')->from('tx_insights_domain_model_comments')->executeQuery()->fetchOne();

        $filteredQb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_insights_domain_model_comments');
        $filteredQb->count('uid')->from('tx_insights_domain_model_comments');

        if ($search !== '') {
            $filteredQb->andWhere($filteredQb->expr()->or(
                $filteredQb->expr()->like('name', $filteredQb->createNamedParameter('%' . $search . '%')),
                $filteredQb->expr()->like('comment', $filteredQb->createNamedParameter('%' . $search . '%')),
                $filteredQb->expr()->like('email', $filteredQb->createNamedParameter('%' . $search . '%'))
            ));
        }
        if (!empty($crdatet)) {
            $filteredQb->andWhere($filteredQb->expr()->gte('crdate', $filteredQb->createNamedParameter($startOfDay, Connection::PARAM_INT)), $filteredQb->expr()->lte('crdate', $filteredQb->createNamedParameter($endOfDay, Connection::PARAM_INT)));
        }
        if (!empty($postfilter)) {
            $filteredQb->andWhere($filteredQb->expr()->eq('post', $filteredQb->createNamedParameter($postfilter, Connection::PARAM_INT)));
        }

        $recordsFiltered = (int) $filteredQb->executeQuery()->fetchOne();

        return new JsonResponse([
            'draw' => $draw,
            'recordsTotal' => (int) $total,
            'recordsFiltered' => (int) $recordsFiltered,
            'data' => array_map(
                function ($row) {
                    $backendUriBuilder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Backend\Routing\UriBuilder::class);
                    $returnUrl = $backendUriBuilder->buildUriFromRoute('tx_insights_comments');
                    $editLink = (string) $backendUriBuilder->buildUriFromRoute('record_edit', ['edit' => ['tx_insights_domain_model_comments' => [$row['uid'] => 'edit']], 'returnUrl' => $returnUrl]);
                    $postEditLink = (string) $backendUriBuilder->buildUriFromRoute('record_edit', ['edit' => ['tx_insights_domain_model_post' => [$row['post'] => 'edit']], 'returnUrl' => $returnUrl]);
                    $_post = $this->postRepository->findByUid($row['post']);
                    $_posttitle = $_post ? $_post->getTitle() : '';
                    $date = new \DateTime();
                    $date->setTimestamp($row['crdate']);

                    return [
                        'uid' => $row['uid'],
                        'name' => $row['name'],
                        'email' => $row['email'],
                        'crdate' => $date->format('d-m-Y'),
                        'post' => $_posttitle,
                        'comment' => $row['comment'],
                        'status' => $row['status'],
                        'editlink' => $editLink,
                        'posteditlink' => $postEditLink,
                    ];
                },
                $rows
            ),
        ]);
    }

    public function authorsbyAjax(): ResponseInterface
    {
        $request = $GLOBALS['TYPO3_REQUEST'];
        $queryParams = $request->getQueryParams();

        $draw = (int) ($queryParams['draw'] ?? 1);
        $start = (int) ($queryParams['start'] ?? 0);
        $length = (int) ($queryParams['length'] ?? 10);
        $search = $queryParams['search']['value'] ?? '';
        $orderColumnIndex = (int) ($queryParams['order'][0]['column'] ?? 0);
        $orderDir = $queryParams['order'][0]['dir'] ?? 'asc';
        $columns = $queryParams['columns'] ?? [];

        $orderField = $columns[$orderColumnIndex]['data'] ?? 'uid';
        $allowedOrderFields = ['uid', 'name', 'email', 'designation', 'intro'];

        if (!in_array($orderField, $allowedOrderFields, true)) {
            $orderField = 'uid';
        }

        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_insights_domain_model_author');
        $qb->select('*')->from('tx_insights_domain_model_author');
        $qb->orderBy($orderField, $orderDir === 'desc' ? 'DESC' : 'ASC')->setFirstResult($start)->setMaxResults($length);

        if ($search !== '') {
            $qb->andWhere($qb->expr()->or(
                $qb->expr()->like('name', $qb->createNamedParameter('%' . $search . '%')),
                $qb->expr()->like('email', $qb->createNamedParameter('%' . $search . '%')),
                $qb->expr()->like('designation', $qb->createNamedParameter('%' . $search . '%')),
                $qb->expr()->like('intro', $qb->createNamedParameter('%' . $search . '%'))
            ));
        }

        $rows = $qb->executeQuery()->fetchAllAssociative();
        $totalQb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_insights_domain_model_author');
        $total = (int) $totalQb->count('uid')->from('tx_insights_domain_model_author')->executeQuery()->fetchOne();

        $filteredQb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_insights_domain_model_author');
        $filteredQb->count('uid')->from('tx_insights_domain_model_author');

        if ($search !== '') {
            $filteredQb->andWhere($filteredQb->expr()->or(
                $filteredQb->expr()->like('name', $filteredQb->createNamedParameter('%' . $search . '%')),
                $filteredQb->expr()->like('email', $filteredQb->createNamedParameter('%' . $search . '%')),
                $filteredQb->expr()->like('designation', $filteredQb->createNamedParameter('%' . $search . '%')),
                $filteredQb->expr()->like('intro', $filteredQb->createNamedParameter('%' . $search . '%'))
            ));
        }

        $recordsFiltered = (int) $filteredQb->executeQuery()->fetchOne();
        $aimagePath = "EXT:insights/Resources/Public/Icons/blank.png";
        /** @extensionScannerIgnoreLine */
        $imagePath = PathUtility::getPublicResourceWebPath($aimagePath);
        return new JsonResponse([
            'draw' => $draw,
            'blankImage' => $imagePath,
            'recordsTotal' => (int) $total,
            'recordsFiltered' => (int) $recordsFiltered,
            'data' => array_map(
                function ($row) {
                    $backendUriBuilder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Backend\Routing\UriBuilder::class);
                    $returnUrl = $backendUriBuilder->buildUriFromRoute('tx_insights_authors');
                    $editLink = (string) $backendUriBuilder->buildUriFromRoute('record_edit', ['edit' => ['tx_insights_domain_model_author' => [$row['uid'] => 'edit']], 'returnUrl' => $returnUrl]);
                    $date = new \DateTime();
                    $date->setTimestamp($row['crdate']);
                    $_row = $this->authorRepository->findByUid($row['uid']);
                    $avtar = $_row ? $_row->getAvtar() : [];
                    $_avtar = "";
                    if (count($avtar)) {
                        foreach ($avtar as $thumbnail) {
                            $_avtar = $thumbnail->getOriginalResource()->getPublicUrl();
                        }
                    }
                    $_sms = $_row ? $_row->getSocialmedia() : [];
                    $smedia = [];
                    if (sizeof($_sms) > 0) {
                        foreach ($_sms as $sm) {
                            $tsm['url'] = $sm->getF17654463090();
                            if (count($sm->getF17654463231())) {
                                foreach ($sm->getF17654463231() as $thumbnail) {
                                    $tsm['icon'] = $thumbnail->getOriginalResource()->getPublicUrl();
                                }
                            }
                            $smedia[] = $tsm;
                        }
                    }
                    return [
                        'uid' => $row['uid'],
                        'slug' => $row['slug'],
                        'name' => $row['name'],
                        'avtar' => $_avtar,
                        'email' => $row['email'],
                        'crdate' => $date->format('d-m-Y'),
                        'designation' => $row['designation'],
                        'intro' => $row['intro'],
                        'socialmedia' => $smedia,
                        'editlink' => $editLink,
                    ];
                },
                $rows
            ),
        ]);
    }

    public function getFolders()
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
        $folderPages = $queryBuilder->select('*')->from('pages')->where($queryBuilder->expr()->eq('doktype', $queryBuilder->createNamedParameter(PageRepository::DOKTYPE_SYSFOLDER, 'integer')), $queryBuilder->expr()->eq('hidden', $queryBuilder->createNamedParameter(0, 'integer')), $queryBuilder->expr()->eq('deleted', $queryBuilder->createNamedParameter(0, 'integer')))->orderBy('sorting')->executeQuery()->fetchAllAssociative();
        $folders = [];
        $folders[0] = "Select";
        foreach ($folderPages as $page) {
            $folders[$page['uid']] = $page['title'];
        }
        return $folders;
    }

    public function getTemplateFiles()
    {
        $__datafile = [];
        $extpath = GeneralUtility::makeInstance(ExtensionManagementUtility::class)::extPath('insights');
        $templateFiles = [
            'Postlist' => "Resources/Private/Templates/Insights/Postlist.html",
            'Tags' => "Resources/Private/Templates/Insights/Tags.html",
            'Postfilter' => "Resources/Private/Templates/Insights/Postfilter.html",
            'Postdetail' => "Resources/Private/Templates/Insights/Postdetail.html",
            'Filterresultlist' => "Resources/Private/Templates/Insights/Filterresultlist.html",
            'Commentform' => "Resources/Private/Partials/Insights/Commentform.html",
            'Breadcrumbs' => "Resources/Private/Templates/Insights/Breadcrumbs.html",
            'Insightelements' => "Resources/Private/Templates/Insights/Insightelements.html",
            'Searchpopular' => "Resources/Private/Templates/Insights/Searchpopular.html",
            'Quicksearch' => "Resources/Private/Templates/Insights/Quicksearch.html",
            'Recommended' => "Resources/Private/Templates/Insights/Recommended.html",
            'Categoryinsights' => "Resources/Private/Templates/Insights/Categoryinsights.html",
        ];
        foreach ($templateFiles as $key => $relativePath) {
            $absolutePath = $extpath . $relativePath;
            if (file_exists($absolutePath)) {
                $__datafile[$key] = file_get_contents($absolutePath);
            }
        }
        return $__datafile;
    }

    public function getCurrentLikestatus($uid)
    {
        $ip = GeneralUtility::getIndpEnv('REMOTE_ADDR');
        $userAgent = GeneralUtility::getIndpEnv('HTTP_USER_AGENT');
        $data = [];
        $this->likesRepository->setStorage();
        $_like = $this->likesRepository->findBy(['post' => $uid, 'like' => 1, 'dislike' => 0]);
        $likecount = sizeof($_like);
        $data['likes'] = $likecount;
        if ($likecount > 0) {
            $data['likedata'] = $_like;
        }
        $_likedis = $this->likesRepository->findBy(['post' => $uid, 'like' => 0, 'dislike' => 1]);
        $dislikecount = sizeof($_likedis);
        $data['dislikes'] = $dislikecount;
        $_cd = $this->likesRepository->findBy(['post' => $uid, 'ipaddress' => $ip, 'browser' => $userAgent]);
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
}
