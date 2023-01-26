<?php

declare(strict_types=1);

namespace Ecommerce121\PartFinder\Controller\Index;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Controller\Result\RedirectFactory;

class PartFinder implements ActionInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var RequestInterface
     */
    private $response;

    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var RedirectFactory
     */
    private $redirectFactory;

    /**
     * @param RequestInterface $request
     * @param JsonFactory $jsonFactory
     * @param CategoryRepositoryInterface $categoryRepository
     * @param RedirectFactory $redirectFactory
     */
    public function __construct(
        RequestInterface $request,
        JsonFactory $jsonFactory,
        CategoryRepositoryInterface $categoryRepository,
        RedirectFactory $redirectFactory
    ) {
        $this->request = $request;
        $this->jsonFactory = $jsonFactory;
        $this->categoryRepository = $categoryRepository;
        $this->redirectFactory = $redirectFactory;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        if (!$this->request->isAjax()) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('/');
            return $resultRedirect;
        }
        $result = [];
        $categoryId = (int) $this->request->getParam('categoryId');
        $category = $this->categoryRepository->get($categoryId);
        $subCategories = $category->getChildrenCategories();
        foreach ($subCategories as $subCategory) {
            switch ($subCategory->getLevel()) {
                case 6:
                    $childCategories = $subCategory->getChildrenCategories();
                    foreach ($childCategories as $childCategory) {
                        $result[] = [
                            'category_id' => $childCategory->getId(),
                            'name' => $childCategory->getName(),
                            'url' => $childCategory->getRequestPath()
                        ];
                    }
                    break;
                default:
                    $result[] = [
                        'category_id' => $subCategory->getId(),
                        'name' => $subCategory->getName(),
                        'url' => $subCategory->getRequestPath()
                    ];
            }
        }
        $resultJson = $this->jsonFactory->create();
        return $resultJson->setData($result);
    }
}
