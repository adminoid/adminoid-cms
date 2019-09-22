# Adminoid CMS

## About Adminoid CMS

When creating the Adminoid CMS, I was inspired by the [modx cms](https://modx.com/), with which I used to have worked. Made with [Laravel](https://laravel.com/) and [Vue.js](https://vuejs.org/).

This is a simple but extensible SEO-friendly content management system,
based on tree-like data representation and management through
TreeView, in which you can simply drag the page nodes for them
moving.

## Features

+  Uri (page addresses) are automatically generated when you change or
moving
+  If the page moves, the system remembers all past uri and
automatically redirects through 301 redirects to the current address
+  Automatic generation of sitemap.xml
+  All pages are one eloquent object, but they can be extended, added
new page types with additional properties and methods
+  The contents of the page are edited by default with wysiwyg
summernote editor compatible with twitter bootstrap. Uploading images to
editor occurs in a folder with the same name as the uri of the page to which
owns the picture
+  The default template is implementing using the twitter bootstrap framework

**Demo:** [cms.adminoid.com](https://cms.adminoid.com/)
**Login:** `mr@adminoid.com`
**Password:** `password`

## Installation

```
git clone https://github.com/adminoid/adminoid-cms.git
cd adminoid-cms
composer update
npm install
cp .env.example .env
```
Then edit .env file for right database and other settings.
```
php artisan key:generate
```
Generate js and css files
```
npm run prod
```
Run dev server
```
php artisan serve
```
