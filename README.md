# Log Favorite Posts

This is a simple yet another favorite post plugin for WordPress.

## Requirements

* WordPress >= 4.6.
* PHP 5.3 or higher.

## Features

* Allows to add a button in the content of each post so that it can be marked / removed as a favorite.
* A Widget to list favorites.
* A shortcode to list favorites.
* REST API field to manage bookmarks only for GET and UPDATE.

## Installation

1. Download the plugin file to your computer and unzip it
2. Using an FTP program, or your hosting control panel, upload the unzipped plugin folder to your WordPress installation's `wp-content/plugins/` directory.
3. Activate the plugin from the Plugins menu within the WordPress admin.

## Usage

* The favorite button is automatically added to the content.
* Show favorite posts in a widget.
* Use the shortcode `[display_favorite_posts]` to display favorited posts. You can also pass a posts per page as a parameter. `[display_favorite_posts posts_per_page="2"]`.

## Unit Testing

Developers who would like to run the existing tests or add their tests to the test suite and execute them will have to follow these steps:

1. `cd` into the plugin directory.
2. Run the plugin tests - `phpunit`

## Rest API

There are 2 different public endpoints that are available.

* Get favorite posts endpoint `GET` `http://your-domain/wp-json/log-favorite-posts/v1/favorite-posts`

> Return Value: `Array of Post Objects`

* Add/Update favorite posts endpoint `PUT` `http://your-domain/wp-json/log-favorite-posts/v1/favorite-posts`

> Params to be sent in the body
`post_id(String)`
Return Value: `Object with message and status or Error (Object)`


