<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Gaiterjones\AntiSpam\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
    }

    public function isSpamRegistration($firstName, $lastName) {
        // Convert both names to lower case to make the check case-insensitive
        $firstNameLower = strtolower($firstName);
        $lastNameLower = strtolower($lastName);
    
        // Check if the first name is contained within the last name
        if (strpos($lastNameLower, $firstNameLower) !== false) {
            return true; // Spam detected
        }
        
        return false; // No spam detected
    }
}

