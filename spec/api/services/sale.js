var frisby = require('frisby');
var config = require('../config.js');
var fakeModels = require('../fake-models');
var faker = require('faker');
var TestRunner = require('../test-runner');

var LocalRunner={
    next:function () {
        var test = this.tests.shift();
        if (test) {
            test().toss();
        }else{
            TestRunner.next();
        }
    },
    tests : [
        createProduct,
        createProduct,
        createProduct,
        createProduct,
        function(){return addProductsToInventory(0,11)},
        function(){return addProductsToInventory(1,11)},
        function(){return addProductsToInventory(2,11)},
        function(){return addProductsToInventory(3,11)}
    ],
    products: []
};

module.exports = {
    prepareInventoryToSale: function(){
        LocalRunner.next();
    },
    createSale: function(){

        console.log("MAOOO1");
        var client = TestRunner.createdClient;
        console.log("MAOOO2"+client.id);
        var products= LocalRunner.products;
        var inventories=TestRunner.inventories;
        var createdSale=null;


        console.log("MAOOO"+client.id);
        var total = 0;
        var productsToSale = [
            {
                product_id: products[0].id,
                quantity: 3
            },
            {
                product_id: products[1].id,
                quantity: faker.random.number(2)
            },
            {
                product_id: products[2].id,
                quantity: faker.random.number(2)
            },
            {
                product_id: products[3].id,
                quantity: faker.random.number(2)
            }
        ];

        for( var i=0; i < productsToSale.length; i++){
            total += productsToSale[i].quantity * products[i].price;
        }

        return frisby.create('Create a Sale')
            .post('branch/1/sale',
                {
                    client_id: client.id,
                    payment_type_id: 1,
                    card_payment_id: null,
                    total: total,
                    client_payment: 1000,
                    products: productsToSale

                })
            .expectStatus(200)
            .expectHeaderContains('content-type', 'application/json')
            .expectJSONTypes('sale' ,{
                id: Number
            })
            .afterJSON(function(body) {
                createdSale = body.sale;
                TestRunner.next();
            });
        //.inspectJSON();
    }
};


function addProductsToInventory (product_number, quantity){
    var that = this;

    return frisby.create('Add Products to Inventory')
        .put('branch/1/inventory/product/'+LocalRunner.products[product_number].id, {
            quantity : quantity
        })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSON({
            success: true
        })
        .afterJSON(function(body) {
            LocalRunner.next();
        });
}

function createProduct (){
    var product = fakeModels.product();

    return frisby.create('Get')
        .post('product', product)
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('product' ,{
            id: Number
        })
        .afterJSON(function(body) {
            LocalRunner.products.push(body.product);
            LocalRunner.next();
        });
}