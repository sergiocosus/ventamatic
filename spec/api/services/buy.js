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
        function(){return addProductsToInventory(0,3)},
        function(){return addProductsToInventory(1,3)},
        function(){return addProductsToInventory(2,3)},
        function(){return addProductsToInventory(3,3)},
        createBuy

    ],
    products: [],
    nuevoInventario:[]
};
var productsToBuy;
var inventories;

module.exports = {
    prepareInventoryToBuy: function(){
        LocalRunner.next();
    }
};

function createBuy(){
    console.log("Buy");

    var supplier = TestRunner.createdSupplier;

    var products= LocalRunner.products;
    inventories=TestRunner.inventories;
    var createdBuy=null;

    var total = 0;
    productsToBuy = [
        {
            product_id: products[0].id,
            quantity: 3
        },
        {
            product_id: products[1].id,
            quantity: faker.random.number(2)+1
        },
        {
            product_id: products[2].id,
            quantity: faker.random.number(2)+1
        },
        {
            product_id: products[3].id,
            quantity: faker.random.number(2)+1
        }
    ];

    for( var i=0; i < productsToBuy.length; i++){
        total += productsToBuy[i].quantity * products[i].global_price;
        console.log("Total: "+total);
        console.log("Cantidad2: "+productsToBuy[i].quantity);
        console.log("Precio: "+products[i].global_price)
    }

    console.log("Supplier");
    console.log(supplier);
    return frisby.create('Create a Buy')
        .post('branch/1/buy',
            {
                supplier_id: supplier.id,
                payment_type_id: 1,
                card_payment_id: null,
                total: total,
                ieps:100,
                iva:100,
                products: productsToBuy

            })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('data.buy' ,{
            id: Number
        })
        .afterJSON(function(body) {
            createdBuy = body.data.buy;

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

    return frisby.create('Get create product Buy')
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


function lessInventory(){

    inventories.forEach(function(inventory){
        delete inventory.updated_at;
        productsToBuy.forEach(function(productToBuy){
            if(inventory.product_id == productToBuy.product_id){
                if(inventory.quantity<productToBuy.quantity)
                    inventory.quantity -= productToBuy.quantity;
                console.log(inventory);
                console.log(productToBuy);
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