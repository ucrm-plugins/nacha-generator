# NACHA Generator Plugin for UCRM

A simple Plugin for UCRM to generate NACHA files.

## Installation

1. Download the [Plugin](https://github.com/ucrm-plugins/nacha-generator/raw/master/nacha-generator.zip) and add it to
the System/Plugins in your UCRM.
2. No configuration is needed, simply click "Enable".
3. Navigate to Add-Ons -> NACHA Generator and follow the instructions throughout the Plugin.

## Versions

#### UCRM
- This has not been tested on any stable versions of UCRM, but the manifest will not allow installation as early as 
v2.15.1
- All testing has been performed against a minimal data set, using v2.16.0-beta1.


## Features

#### Localization
- Some minimal currency conversions using the UCRM locale settings currently exist, but will need to be more thoroughly
tested before claiming any localization support.
- No translations have been done, as of yet!


## About

### Requirements
- This package will be maintained in step with the PHP version used by UCRM to ensure 100% compatibility.
- Any packages required that are not already enabled in the default UCRM installation are included with this Plugin 
in the accompanying `vendor/` folder and can be updated and maintained manually using
[composer](https://getcomposer.org/) if desired.

### Related Packages
[mvqn/ucrm-plugin-sdk](https://github.com/mvqn/ucrm-plugin-sdk)\
An alternative UCRM SDK designed to ease Plugin development.

### Submitting bugs and feature requests
Bugs and feature request are tracked on [Github](https://github.com/ucrm-plugins/nacha-generator/issues)

### Author
Ryan Spaeth <[rspaeth@mvqn.net](mailto:rspaeth@mvqn.net)>

### License
This module is licensed under the MIT License - see the `LICENSE` file for details.

### Acknowledgements
Credit to the Ubiquiti Team for giving us the luxury of Plugins!
