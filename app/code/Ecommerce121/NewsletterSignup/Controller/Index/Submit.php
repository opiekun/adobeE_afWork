<?php

declare(strict_types=1);

namespace Ecommerce121\NewsletterSignup\Controller\Index;

use Ecommerce121\NewsletterSignup\Model\NewsletterSignupProcessor;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;

class Submit implements ActionInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var NewsletterSignupProcessor
     */
    private $newsletterSignupProcessor;

    /**
     * @param Context $context
     * @param Validator $validator
     * @param NewsletterSignupProcessor $newsletterSignupProcessor
     */
    public function __construct(
        Context $context,
        Validator $validator,
        NewsletterSignupProcessor $newsletterSignupProcessor
    ) {
        $this->request = $context->getRequest();
        $this->resultFactory = $context->getResultFactory();
        $this->messageManager = $context->getMessageManager();
        $this->validator = $validator;
        $this->newsletterSignupProcessor = $newsletterSignupProcessor;
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $params = $this->request->getParams();
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        try {
            if (!$this->validator->validate($this->request)) {
                throw new LocalizedException(__('Invalid form key. Please, reload the page and try again.'));
            }

            if (!$this->newsletterSignupProcessor->process($params)) {
                throw new LocalizedException(__('Something went wrong, please try again later'));
            }

            return $result->setData(['success' =>  true]);
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage(__('Something went wrong, please try again later'));
            return $result->setData(['success' => false]);
        }
    }
}
