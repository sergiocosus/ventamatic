var frisby = require('frisby');
var config = require('../config.js');
var fakeModels = require('../fake-models');
var faker = require('faker');
var TestRunner = require('../test-runner');

var inventory = require('../services/inventory');


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
        function(){return addProductsToInventory(3,11)},
        function(){
            return inventory.getInventory(function(){LocalRunner.next()});
        },
        createSale,
        checkInventory
    ],
    products: [],
    nuevoInventario:[]
};
var productsToSale;
var inventories;

module.exports = {
    prepareInventoryToSale: function(){
        LocalRunner.next();
    }
};

function createSale(){
    console.log("Sale");

    var client = TestRunner.createdClient;

    var products= LocalRunner.products;
    inventories=TestRunner.inventories;
    var createdSale=null;

    var total = 0;
    productsToSale = [
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
        total += productsToSale[i].quantity * products[i].global_price;

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
        .expectJSONTypes('data.sale' ,{
            id: Number
        })
        .afterJSON(function(body) {
            createdSale = body.data.sale;
            TestRunner.total_sale=total;

            LocalRunner.next();
        });
    //.inspectJSON();
}


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
        .expectJSONTypes('data.product' ,{
            id: Number
        })
        .afterJSON(function(body) {
            LocalRunner.products.push(body.data.product);
            LocalRunner.next();
        });
}

function checkInventory(){
    inventories.forEach(function(inventory){
        delete inventory.updated_at;
        productsToSale.forEach(function(productToSale){
            if(inventory.product_id == productToSale.product_id){
                inventory.quantity -= productToSale.quantity;
                console.log(inventory);
                console.log(productToSale);
            }
        });
    });

    return frisby.create('check a Inventory')
        .get('branch/1/inventory',{
            json:false
        })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSON('data.inventories' , inventories)
        .afterJSON(function(body) {
            TestRunner.inventories=body.data.inventories;
            LocalRunner.next();
        });
}

function lessInventory(){

    inventories.forEach(function(inventory){
        delete inventory.updated_at;
        productsToSale.forEach(function(productToSale){
            if(inventory.product_id == productToSale.product_id){
                if(inventory.quantity<productToSale.quantity)
                inventory.quantity -= productToSale.quantity;
                console.log(inventory);
                console.log(productToSale);
            }
        });
    });

    return frisby.create('check a Inventory')
        .get('branch/1/inventory',{
            json:false
        })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSON('data.inventories' , inventories)
        .afterJSON(function(body) {
            TestRunner.inventories=body.data.inventories;
            LocalRunner.next();
        });

}