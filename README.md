# Magento 2 Gift Wrap GraphQL / PWA

Mageplaza Gift Wrap Extension supports getting and pushing data on the website with GraphQl.

## How to install

Run the following command in Magento 2 root folder:

```
composer require mageplaza/module-gift-wrap-graphql
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```

## How to use

To start working with **Gift Wrap GraphQL** in Magento, you need to:

- Use Magento 2.3.x. Return your site to developer mode
- Install [chrome extension](https://chrome.google.com/webstore/detail/chromeiql/fkkiamalmpiidkljmicmjfbieiclmeij?hl=en) (currently does not support other browsers)
- Set **GraphQL endpoint** as `http://<magento2-3-server>/graphql` in url box, click **Set endpoint**. (e.g. http://develop.mageplaza.com/graphql/ce232/graphql)
- Perform a query in the left cell then click the **Run** button or **Ctrl + Enter** to see the result in the right cell
- You can see more details [here](https://documenter.getpostman.com/view/5187684/SzKTuyMH?version=latest).
