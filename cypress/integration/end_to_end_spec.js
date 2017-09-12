describe('End to End Test', function () {

    before(function () {
        Cypress.config('baseUrl', 'http://ontrack.dev')

    });

    context('Get Pages', function () {
        it('get home page', function () {
            cy.visit('/logout')
            cy.get('#welcome-login');
        });
        it('get login page', function () {
            cy.visit('/users/login')
            cy.contains('form');
        });
    });

    context('Dashboard', function () {
        Cypress.addParentCommand('loginByJSON', function (username, password) {

            Cypress.Log.command({
                name: 'loginByJSON',
                message: username + ' | ' + password
            })

            return cy.request({
                method: 'POST',
                url: '/login',
                body: {
                    username: username,
                    password: password
                }
            })
        })

        beforeEach(function () {
            // login before each test
            cy.loginByJSON('admin', 'turgut')
        })

        it('can get to dashboard', function () {
            cy.visit('/');
            var monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];

            var d = new Date();


            cy.contains(monthNames[d.getMonth()]);
        })

        it('can go to events list', function () {
            cy.visit('/');
            var monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];

            var d = new Date();


            cy.contains(monthNames[d.getMonth()]);

        })


        it('can add new events', function () {
            cy.visit('/events/add');
            cy.get('form');
            cy.get('textarea[name=note]').type('something{enter}')
            cy.get('input[name=hours]').type('3')
            cy.get('input[name=minutes]').type('15{enter}')
        })

        it('can show event', function () {
            cy.visit('/events/view/25');
            cy.get('h3').should('contain', 'Event')
        })

        it('can show dates', function () {
            cy.visit('/events/dates');
            cy.get('h3').should('contain', 'Dates')
        })

        it('can show report of today', function () {
            cy.visit('/events/report');
            cy.get('h3').should('contain', 'Report')
        })

        it('can show users', function () {
            cy.visit('/users');
            cy.get('h3').should('contain', 'Users')
        })

        it('can add a new user', function () {
            cy.visit('/users/add');
            cy.get('form');
            cy.get('input[name=username]').type('mtkocak')
            cy.get('input[name=email]').type('mtkocak@gmail.com{enter}')
        })

        it('can edit user', function () {
            cy.visit('/users/edit/4');
            cy.get('form');
            cy.get('input[name=username]').type('mtkocak')
            cy.get('input[name=email]').type('mtkocak@gmail.com{enter}')
        })

        it('can invite a new user', function () {
            cy.visit('/');
            cy.get('a[href$="/users/invite"]').click()
            cy.get('input[name=email]').type('mtkocak@gmail.com{enter}')
        })

        it('can show profile', function () {
            cy.visit('/');
            cy.get('a[href$="/users/view/1"]').click()

        })

        it('can show settings', function () {
            cy.visit('/');
            cy.get('a[href$="/users/edit/1"]').click()
            cy.get('legend').should('contain', 'Edit User')
        })

        it('can log out', function () {
            cy.visit('/logout');
            cy.get('#welcome-login');
        })

    });

    context('Outsider', function () {

        beforeEach(function () {
            cy.visit('/logout');
        })

        it('forgot password', function () {
            cy.visit('/forgot');
            cy.get('input[name=email]').type('mtkocak@gmail.com{enter}')
        });

        it('after invitation is confirmed, registers using link', function () {
            cy.visit('users/register?invitation=4fe16a4ac7b6518eb81acec6cf3974d6')
            cy.contains('form');
        });
    });
});
