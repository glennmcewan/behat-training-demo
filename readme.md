# Behat Training
This is the repo created to demonstrate how we can use Behat on a very simple Laravel 5 todo app.

- `git clone`
- `composer install`
- Set DB credentials in `.env` file
- `php artisan migrate`

To run Behat tests:
`vendor/bin/behat`

## Selenium Setup Instructions
These are documented in a separate file to keep things clean.

You can view the file in this repo: [selenium-setup.md](selenium-setup.md).

## TODO
- [x] Write up instructions on how to set up Selenium.
- [ ] Improve ApplicationStateContext; currently a very **wrong** way of truncating the DB