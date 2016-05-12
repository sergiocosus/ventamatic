var frisby = require('frisby');
var config = require('../config.js');
var fakeModels = require('../fake-models');
var faker = require('faker');
var TestRunner = require('../test-runner');

module.exports = {
    createSale: function(){
        // var branchRoleBR = fakeModels.supplier();
        var client = TestRunner.clients[0];
       var productos= TestRunner.products;
        var products = [
            {
                product_id: 1,
                quantity: faker.random.number(15)
            },
            {
                product_id: 2,
                quantity: faker.random.number(15)
            },
        ];
        var total = 0;
        for( var product in products){
            total += product.quantity * product.price;
        }

        return frisby.create('Create a Sale')
            .post('branch/1/sale',
                {
                    client_id: client.id,
                    payment_type_id: 1,
                    card_payment_id: null,
                    total: 234.23,
                    client_payment: 500,
                    products: [
                        {
                            product_id: 1,
                            quantity: faker.random.number(15)
                        },
                        {
                            product_id: 2,
                            quantity: faker.random.number(15)
                        },
                        {
                            product_id: 3,
                            quantity: faker.random.number(15)
                        }
                    ]

                })
            .expectStatus(200)
            .expectHeaderContains('content-type', 'application/json')
            .expectJSONTypes('branchRole' ,{
                id: Number
            })
            .afterJSON(function(body) {
                createdBranchRole = body.branchRole;
                TestRunner.next();
            });
        //.inspectJSON();
    }
};
