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
        function(){return addProductsToInventory(0,3)},
        function(){return addProductsToInventory(0,3)},
        createMovementInventory

    ],
    products: [],
    nuevoInventario:[]
};


module.exports = {

    prepareInventoryMovement: function(){
        LocalRunner.next();
    },

    getInventory: function(callback){
        return frisby.create('Get a inventory')
            .get('branch/1/inventory',{
                json:false
            })
            .expectStatus(200)
            .expectHeaderContains('content-type', 'application/json')
            .expectJSONTypes('data.inventories' ,[{
                id: Number
            }])
            .afterJSON(function(body) {
                TestRunner.inventory=body.data.inventories;
                if(callback){
                    callback();
                } else {
                    TestRunner.next();
                }
            });
    }

   };

function createMovementInventory (product_number, quantity){
    var that = this;

    return frisby.create('Create Movement Inventory')
        .patch('branch/1/inventory/'+LocalRunner.products[product_number].id, {
            quantity : quantity
        })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSON({
            status:'success'
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