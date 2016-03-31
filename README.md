# AWS EC2 instance control panel

Simple application for starting and stopping AWS EC2 instances.

## Requirements

* Silex framework
* AWS SDK 
* Twig
* Bootstrap

## Installation

1) Install required packages via composer

2) copy `config.local.php.dist` to `config.local.php`

3) configure parameters in `config.local.php`
 
4) set *DryRun* option to *false* to allow controlling EC2 instances 

## Security warning

**Remember to always keep your AWS API keys in secret!**

## License

This application is released under the MIT License.
