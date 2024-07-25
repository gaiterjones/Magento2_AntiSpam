<?php
declare(strict_types=1);

namespace Gaiterjones\AntiSpam\Plugin\Controller\Account;

use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\UrlFactory;
use Magento\Framework\Message\ManagerInterface;
use Gaiterjones\AntiSpam\Helper\Data as Helper;

class RestrictCustomerRegistration
{

    /** @var \Magento\Framework\UrlInterface */
    protected $urlModel;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    private $_helper;

    /**
     * RestrictCustomerEmail constructor.
     * @param UrlFactory $urlFactory
     * @param RedirectFactory $redirectFactory
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        UrlFactory $urlFactory,
        RedirectFactory $redirectFactory,
        ManagerInterface $messageManager,
        Helper $helper

    )
    {
        $this->urlModel = $urlFactory->create();
        $this->resultRedirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->_helper = $helper;
    }

    /**
     * @param \Magento\Customer\Controller\Account\CreatePost $subject
     * @param \Closure $proceed
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function aroundExecute(
        \Magento\Customer\Controller\Account\CreatePost $subject,
        \Closure $proceed
    )
    {
        /** @var \Magento\Framework\App\RequestInterface $request */

        // Retrieve the request object
        $request = $subject->getRequest();
        
        // Get the first name and last name from the request
        $firstName = $request->getParam('firstname');
        $lastName = $request->getParam('lastname');

        if ($firstName && $lastName)
        {
            if ($this->_helper->isSpamRegistration($firstName,$lastName))
            {
                // spam registration detected
                $this->messageManager->addErrorMessage(
                    'Registration data is not valid please contact Customer Service.'
                );

                $this->log('Spam registration data detected for name '. $firstName. ' '. $lastName);

                $defaultUrl = $this->urlModel->getUrl('*/*/create', ['_secure' => true]);
                /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
    
                return $resultRedirect->setUrl($defaultUrl);
            } 

        }

        return $proceed();
    }

    public function log($text)
	{
		$writer = new \Zend_Log_Writer_Stream(BP . '/var/log/gaiterjones_antispam.log');
		$logger = new \Zend_Log();
		$logger->addWriter($writer);
		$logger->info($text);
	}
}