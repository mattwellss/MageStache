# MageStache

### Magento module for incremental Mustache theme integration

## Introduction

MageStache is a module designed to support partial Mustache template integration with Magento. Traditional phtml and Mustache templates can live side-by-side and render each other without any customization required.

### Quick Example

```mustache
{{! The pragma below is required }}
{{%MAGENTO-TEMPLATE}}
<!doctype html>
<html>
<head>
    {{> head}} {{! This one is phtml }}
</head>
<body>
{{> after_body_start}}
{{> header}}
{{> breadcrumbs}}
    <div class="content">
    {{> global_messages}}
    {{> content}} {{! More phtml. Maybe it contains Mustache, though? }}
    </div>
{{> footer_before}}
{{> footer}}
{{> global_cookie_notice}}
{{> before_body_end}}
</body>
</html>
```

## Installation

```
composer require mattwellss/magestache
```

It's techincally possible to install this module without Composer, but it's unsupported, so no examples are provided.

## Core Concepts

### Mustache / PHTML Integration

Of importance to any user looking to add Mustache templates to an existing app is the ability to continue using Magento as before in other cases. This module supports that by treating Mustache's `{{> partial}}` syntax as (essentially) `getChildHtml`.

### Data Conversion

Potentially _the_ primary difference between Magento's standard phtml template rendering and Mustache template rendering is the lazy and eager loading of data (respectively). As much as possible, Magento prevents unnecessary processing by waiting as long as possible to load collections and render blocks. On the other hand, Mustache expects data to be available upon render. MageStache gets around this issue with the use of two fundamental concepts.
 
#### Nested "Data Block"

This is a child block of the Mustache template (another Magento block itself). When applied to a template, this block is used as the data source for rendering. In this way, a non-Mustache Magento block–a cart, product, or widget–can provide data to the Mustache template.
 
#### Data Conversion via Fieldsets

Magento's transformation functionality enabled by `copyFieldset` is quite powerful but little-used outside of core. By specifying a conversion (generally via layout XML), data preparation normally done "in the moment" in phtml can be handled ahead of time for Mustache. For example, the product collection and toolbar of a product listing block can be prepared and added to the data hash for a Mustache product listing template.

## Contributing

To keep track of features currently in-progress, please check the issues. To propose a new feature or request a bug fix, please open a new issue.

### Bug Fix Requests

Please include thorough steps to reproduce the bug. Before creating a pull request to fix a bug, please open an issue.

### New Feature Requests

As with bug fixes, opening an issue is preferred.
 
## More Examples

TODO!