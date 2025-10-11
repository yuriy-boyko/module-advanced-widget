# MageOS AdvancedWidget Module for Magento

Add configurable multi-row CMS Widgets with image picker component, product picker component, select fields and much more.

---

## Overview

The **AdvancedWidget** module allows you to define multi-row CMS widgets.
These features combined with MageOS_PageBuilderWidget module (that is explicit dependency) make finally possible to develop custom pagebuilder components with own preview and a large set of configurations.
Complex pagebuilder ui components development is no more needed.


## 🚀 Features

> 1) This module let you specify Title separators inside widgets
   ![title section](./doc/title-section_screenshot.png)

> 2) This module let you specify multiple "repeatable" sections where you can specify unlimited rows inside widgets
   ![repeatable section](./doc/repeatable-section_screenshot.png)
>
>> 2.1) Each item field can receive a dedicated tooltip
   ![repeatable section](./doc/repeatable-section-tooltip_screenshot.png)
> 
>> 2.2) Each field is validated and ask to be required for compilation
   ![repeatable section](./doc/repeatable-section-validation_screenshot.png)
>
>> 2.3) Items can be sorted
   ![repeatable section](./doc/repeatable-section-sorter_screenshot.png)
> 
>> 2.4) You can specify whether a field is editable directly in the main box or whether it must be edited in the detail modal.
   ![repeatable section](./doc/repeatable-section-row_screenshot.png)
 
> 3) Row item images fields are available
   ![image field](./doc/image-field_screenshot.png)
   ![image field selection](./doc/image-field-selection_screenshot.png)

> 4) Row item select fields are available
   ![select field](./doc/select-field_screenshot.png)

> 5) Row item product fields are available
   ![product field](./doc/product-field_screenshot.png)
   ![product field selection](./doc/product-field-selection_screenshot.png) 

 
## 🔧 Installation

1. Install it into your Mage-OS/Magento 2 project with composer:
    ```
    composer require mage-os/module-advanced-widget
    ```

2. Enable module
    ```
    bin/magento module:enable MageOS_AdvancedWidget
    bin/magento setup:upgrade
    ```

## 🤝 Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.


## 📄 License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

### Attribution

This software uses Open Source software. See the [ATTRIBUTION](ATTRIBUTION.md) page for these projects.
