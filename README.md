# Sanctum API Refresh

Often we need to make a refresh token for an API. Under the hood, 
Sanctum does not provide this. Instead, they provide Stateful Authentication for
Frontends. This can be a pain if the front end is not in the same domain.

The needs of refresh token is not only that. Mobile authentication in sanctum requires user to re-login
whenever the token expires. Of course, this issue can be solved by putting the expiration to zero.
However, it's kinda less secure?

That's why this boilerplate project came. This project provide Sanctum Refresh Token that check can only
be used once to refresh and automatically revoke old token. This project can also smartly put refresh
token in cookie if the target platform support it.

> This project involves as minimum override as possible. Making it possible to upgrade sanctum without breaking the 
> application

# Flow

Simply login, and when something expires. Hit refresh. The app will automatically look for cookie
if existed, else the app will look for request body.

# Installation

Clone this repository: `git clone https://github.com/albetnov/sanctum-api-refresh.git`. Then, simply run `composer install`

# Testing

Simply run
```bash
composer test
```
