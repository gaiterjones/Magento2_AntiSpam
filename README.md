# Mage2 Module Gaiterjones AntiSpam


Bot registration spam often uses dummy names such as Danadok DanadokPO this module
will try and detect some basic spam registrations

1. reject a surname that includes the firstname

TEST: 

bin/magento antispam:test --name "Danadok DanadokPO"
Danadok DanadokPO - SPAM DETECTED



