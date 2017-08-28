# PHP Wrapper for MailChimp API v3 [![CircleCI](https://circleci.com/gh/adrian7/mailchimp-api-v3/tree/master.svg?style=svg)](https://circleci.com/gh/adrian7/mailchimp-api-v3/tree/master)

* [Installation](#installation)
* [Usage](#usage)
    * [Pagination](#pagination)
    * [Filtering](#filtering)
    * [Partial Response](#partial-response)
    * [Behind Proxy](#behind-proxy)
* [Examples](#examples)
    * [Create lists](#create-lists)
    * [Subresources](#subresources)
    * [Proxy](#proxy)
* [Further documentation](#further-documentation)

# Installation

```
composer require adrian7/mailchimp-api-v3
```

# Usage
There's one method to rule them all:

```php
// $arguments is used as POST data or GET parameters, depending on the method used.
request($resource, $arguments = [], $method = 'GET')
```

But its clever enough to map these calls aswell:

```php
get($resource, array $options = [])
head($resource, array $options = [])
put($resource, array $options = [])
post($resource, array $options = [])
patch($resource, array $options = [])
delete($resource, array $options = [])
```

### Pagination
_We use `offset` and `count` in the query string to paginate data, because it provides greater control over how you view your data. Offset defaults to 0, so if you use offset=1, you'll miss the first element in the dataset. Count defaults to 10._

Source: http://kb.mailchimp.com/api/article/api-3-overview

### Filtering
_Most endpoints don't currently support filtering, but we plan to add these capabilities over time. Schemas will tell you which collections can be filtered, and what to include in your query string._

Source: http://kb.mailchimp.com/api/article/api-3-overview

### Partial Response
_To cut down on data transfers, pass a comma separated list of fields to include or exclude from a certain response in the query string. The parameters `fields` and `exclude_fields` are mutually exclusive and will throw an error if a field isn't valid in your request._

Source: http://kb.mailchimp.com/api/article/api-3-overview

### Behind Proxy
If you are behind a proxy, you can use `setProxy` directly on the class. 

`setProxy(host : string, port : int, [ssl : bool = false], [username = null], [password = null]);`

See the [example](#proxy).

# Examples

All queries will return an object containing the parsed json response.

```php
$mc = new Mailchimp('<api-key>', '<guzzle-options[array]>');

// Get 10 lists starting from offset 10 and include only a specific set of fields
$result = $mc->request('lists', [
    'fields' => 'lists.id,lists.name,lists.stats.member_count',
    'offset' => 10,
    'count' => 10
]);

// Will fire this query: 
// GET https://us1.api.mailchimp.com/3.0/lists?fields=lists.id,lists.name,lists.stats.member_count&count=10

var_dump($result);

```
    
### Create list

```php
// All these fields are required to create a new list.
$result = $mc->post('lists', [
    'name' => 'New list',
    'permission_reminder' => 'You signed up for updates on mailchimp-api-v3.',
    'email_type_option' => false,
    'contact' => [
        'company' => 'Doe Ltd.',
		'address1' => 'DoeStreet 1',
		'address2' => '',
		'city' => 'Doesy',
		'state' => 'Doedoe',
		'zip' => '1672-12',
		'country' => 'US',
		'phone' => '55533344412'
    ],
    'campaign_defaults' => [
        'from_name' => 'John Doe',
        'from_email' => 'john@doe.com',
        'subject' => 'My new campaign!',
        'language' => 'US'
    ]
]);
```

### Subresources

```php
$result = $mc->get('lists/e04d611199', [
    'fields' => 'id,name,stats.member_count'
]);
```

### Proxy

```php
$mc->setProxy('https://127.0.0.1', 10, true, 'username', 'password');

$result = $mc->get('lists/e04d611199', [
    'fields' => 'id,name,stats.member_count'
]);
```

# Further documentation
You should read through Mailchimp's API v3 [documentation](http://kb.mailchimp.com/api/).
