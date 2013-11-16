Simple wildcard component
=========================

Example:
```php
<?php
use Kir\StringUtils\Matching\Wildcards\Pattern;

echo Pattern::create('Kir\\*\\Wildcards\Pattern', 'Kir\\StringUtils\\Matching\\Wildcards\\Pattern') ? 'is matching' : 'is not matching';
```