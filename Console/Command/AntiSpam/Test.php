<?php

declare(strict_types=1);

namespace Gaiterjones\AntiSpam\Console\Command\AntiSpam;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\State as AppState;
use Gaiterjones\AntiSpam\Helper\Data as Helper;

/**
 * Class Test
 *
 */
class Test extends Command
{
    private const OPTION_NAME = 'name';

    /**
     * @var ScopeConfigInterface
     */
    private $_scopeConfig;

    /**
     * @var AppState
     */
    private $_appState;

    private $_helper;


    public function __construct(
        ScopeConfigInterface $scopeConfig,
        AppState $appState,
        Helper $helper
    ) {
        $this->_appState = $appState;
        $this->_scopeConfig = $scopeConfig;
        $this->_helper = $helper;
        parent::__construct();
    }

    /**
     * Initialization of the command
     *
     * @return void
     */
     protected function configure(): void
     {
         $this
             ->setName('antispam:test')
             ->setDescription('Test login spam detection')
             ->addOption(
                 self::OPTION_NAME,
                 null,
                 InputOption::VALUE_REQUIRED,
                 'Name'
             );
     }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name=$input->getOption(self::OPTION_NAME);

        $names=explode(' ',$name);

        //print_r($names);
        if ($this->_helper->isSpamRegistration($names[0],$names[1]))
        {
            echo $names[0]. ' '.$names[1].' - SPAM DETECTED'.PHP_EOL;
        } else {
            echo $names[0]. ' '.$names[1].' - NO SPAM DETECTED'.PHP_EOL;
        }

        return 0;
        
    }

}
