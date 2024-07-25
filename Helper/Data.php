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

    const MIN_SUBSTRING_LENGTH = 4; // Minimum length of substring to consider it suspicious
    const MAX_LEVENSHTEIN_DISTANCE = 2; // Maximum allowed Levenshtein distance

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
    }

    public function isSpamRegistration($firstName, $lastName)
    {
        // Convert both names to lower case to make the check case-insensitive
        $firstNameLower = strtolower($firstName);
        $lastNameLower = strtolower($lastName);

        // Check if the first name is contained within the last name with a minimum length
        if (strlen($firstNameLower) >= self::MIN_SUBSTRING_LENGTH &&
            strpos($lastNameLower, $firstNameLower) !== false &&
            (strpos($lastNameLower, $firstNameLower) !== 0 && strpos($lastNameLower, $firstNameLower) + strlen($firstNameLower) != strlen($lastNameLower))) {
            return true; // Spam detected
        }

        // Check Levenshtein distance between first name and last name
        if (levenshtein($firstNameLower, $lastNameLower) <= self::MAX_LEVENSHTEIN_DISTANCE) {
            return true; // Spam detected
        }

        return false; // No spam detected
    }
}

