# Installation

Install the module via composer:

```bash
composer require flamix/bitrix24-orders-integration
php bin/magento module:enable Flamix_Bitrix24OrdersIntegration
php bin/magento setup:upgrade
php bin/magento cache:clean
```

## TODO:
- [x] Orders synchronization
- [x] UTM tracking
- [ ] Status synchronization
- [ ] Payments synchronization (sales_order_payment_pay)
