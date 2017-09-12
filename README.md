# OnTrack Time Management Web App

[![Build Status](https://img.shields.io/travis/midorikocak/ontrack/master.svg?style=flat-square)](https://travis-ci.org/midorikocak/ontrack)
[![License](https://img.shields.io/packagist/l/cakephp/app.svg?style=flat-square)](https://packagist.org/packages/cakephp/app)

A REST compliant time management application.

## Installation

If Composer is installed globally, run

```bash
composer install
```

You can now either use your machine's webserver to view the default home page, or start
up the built-in webserver with in the root directory of the app.:

```bash
bin/cake server -p 8765
```

Then visit `http://localhost:8765` to see the welcome page.

## Configuration

Read and edit `config/app.php` and setup the `'Datasources'` and any other
configuration relevant for your application.

## Testing

The application uses phpunit for unit testing and cypress for e2e tests.

## Intstructions

1. ~~User must be able to create an account and log in.~~
2. ~~User can add (and edit and delete) a row what he has worked on, what date, for how long.~~
3. ~~User can add a setting (Preferred working hours per day).~~
4. ~~If on a particular date a user has worked under the PreferredWorkingHourPerDay, these rows are red, otherwise green.~~
5. ~~Implement at least three roles with different permission levels: a regular user would only be able to CRUD on their owned records, a user manager would be able to CRUD users, and an admin would be able to CRUD all records and users.~~
6. ~~Filter entries by date from-to.~~
7. ~~Export the filtered times to a sheet in HTML:~~
 - Date: 21.5
 - Total time: 9h
 - Notes:
   * Note1
   * Note2
   * Note3

8. ~~REST API. Make it possible to perform all user actions via the API~~, including authentication.
9. In any case, you should be able to explain how a REST API works and demonstrate that by creating functional tests that use the REST Layer directly. Please be prepared to use REST clients like Postman, cURL, etc. for this purpose.
10. All actions need to be done client side using AJAX, refreshing the page is not acceptable.
11. ~~You will not be marked on graphic design, however, do try to keep it as tidy as possible.~~
12. Unit and e2e tests are not optional, they are mandatory
13. ~~New users need to **verify** their account by email. Users should not be able to log in until this verification is complete.~~
14. ~~The login process should include a CAPTCHA field using the Google API.~~
15. When a user fails to log in three times in a row, his or her account should be blocked automatically, and only admins and managers should be able to unblock it.
16. ~~An admin should be able to invite someone to the application by typing an email address in an input field; the system should then send an invitation message automatically, prompting the user to complete the registration.~~
17. ~~Users have to be able to upload and change their profile picture.~~


## Todo

1. ~~Readme~~
2. ~~POC~~
3. ~~Events List for day~~
4. ~~REST Routes~~
5. ~~Unit Tests~~
6. Cypress Tests
7. ~~Create Report~~
8. Installation