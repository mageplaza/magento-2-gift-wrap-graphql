# Magento 2 Gift Wrap GraphQL / PWA

**Magento 2 Gift Wrap GraphQL is a part of Mageplaza Gift Wrap extension that adds GraphQL features. This supports PWA compatibility.** Mageplaza Gift Wrap Extension assists you in getting and pushing data on the website with GraphQL.

[Mageplaza Gift Wrap for Magento 2](https://www.mageplaza.com/magento-2-gift-wrap/) enables gift wrapping like at the offline stores.

The store admin can add the gift wrapping option on multiple pages so that customers can notice and choose to use it anytime while they are browsing across different pages on your website. The most common pages to display the “Add Your Gift wrap” suggestions are the Product Detail Page, Shopping Cart Page, and Checkout Page. 

When customers click on the “Add Your Gift Wrap,” a pop-up with various beautiful wrapping papers will appear and showcase in the slider mode. Customers can pick up any option from the slider for their orders. After wrapping the items, customers can also change the wrapping paper easily via the pop-up. 

When customers purchase multiple items at once, they can also wrap all of them in one gift box or wrap each of the items individually. There will be a notification about wrapping all products together. Otherwise, customers can switch to the separate wrapping option. 

The gift wrap papers can be organized into different categories. If your store has many wrapping papers, this will help customers sort out their favorite types quickly. You can create multiple categories from the admin backend based on the types of wrapping papers. 

The extension also enables customers to add cards to their orders when they want to wrap items as a gift to send to someone. The card’s message is up to customers and will be written nicely on the card before sending it to the receivers. This customized feature enhances the customer experience and makes them feel like they’re actually involved in the gift wrapping process. 

Offering a gift wrap option can generate extra revenue for your online store. You can set a fixed price for this service or flexibly calculate it based on the quantity of the items. However, this service should be an incentive for customers to purchase products, so the fee should be a small amount that customers are willing to pay. At the peak season, this gift wrap will be an appealing offering that differentiates your store from others. 

## 1. How to install

Run the following command in Magento 2 root folder:

```
composer require mageplaza/module-gift-wrap-graphql
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```

**Note:**
Magento 2 Gift Wrap GraphQL requires installing [Mageplaza Gift Wrap](https://www.mageplaza.com/magento-2-gift-wrap/) in your Magento installtion. 

## 2. How to use

To start working with **Gift Wrap GraphQL** in Magento, you need to:

- Use Magento 2.3.x. Return your site to developer mode
- Install [chrome extension](https://chrome.google.com/webstore/detail/chromeiql/fkkiamalmpiidkljmicmjfbieiclmeij?hl=en) (currently does not support other browsers)
- Set **GraphQL endpoint** as `http://<magento2-3-server>/graphql` in url box, click **Set endpoint**. (e.g. `http://develop.mageplaza.com/graphql/ce232/graphql`)
- Perform a query in the left cell then click the **Run** button or **Ctrl + Enter** to see the result in the right cell
- You can see more details [here](https://documenter.getpostman.com/view/5187684/SzKTuyMH?version=latest). 

![gift warp](https://i.imgur.com/PTVKYfp.jpg)

## 3. Devdocs
- [Magento 2 Gift Wrap API & examples](https://documenter.getpostman.com/view/5187684/SzKSTKv8?version=latest) 
- [Magento 2 Gift Wrap GraphQL & examples](https://documenter.getpostman.com/view/5187684/SzKTuyMH?version=latest)

Click on Run in Postman to add these collections to your workspace quickly.

![Magento 2 blog graphql pwa](https://i.imgur.com/lhsXlUR.gif)

## 4. Contribute to this module
Feel free to **Fork** and contribute to this module. 

You can create a pull request, and we will consider to merge your proposed changes in the main branch. 

## 5. Get support 
- Feel free to contact us if you have any question. Our dedicated support is always available to help you. 
- If you like this post, please give it a **Star** ![star](https://i.imgur.com/S8e0ctO.png)

