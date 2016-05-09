
var frisby = require('frisby');
var faker = require('faker');
var config = require('./config.js');
var fakeModels = require('./fake-models');
faker.locale = "es_MX";

var products = [];

var createdProduct = null;
var createdSupplier = null;
var createdBrand = null;
var createdCategory = null;
var createdClient = null;
var createdSupplierCategory=null;
var createdRole=null;

frisby.globalSetup({ // globalSetup is for ALL requests
    request: {
        headers: {
            'Accept': 'application/json'
        },
        json:true,
        baseUri: config.url,
        inspectOnFailure: true
    }
});

var tests = [
    auth,
    getLoggedUser,
    createProduct,
    updateAProduct,
    addProductsToInventory,
    DeleteProduct,
    getADeletedProduct,
    createABrand,
    updateABrand,
    DeleteBrand,
    getADeletedBrand,
    createACategory,
    updateACategory,
    DeleteCategory,
    getADeletedCategory,
    createAClient,
    updateAClient,
    DeleteClient,
    getADeletedClient,
    createASupplierCategory,
    updateASupplierCategory



];

function next(){
    var test = tests.shift();
    if(test){
        test().toss();
    }
}

next();

function auth(){
    return frisby.create('Auth Ventamatic APi')
        .post('auth',
            config.userCredentials
        )
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes({
            token: String
        })
        .afterJSON(function(body) {
            config.token = body.token;
            next();
        });
}



function getLoggedUser(){
    return frisby.create('Get')
        .get('user/1?token='+config.token,{
            json:false
        })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('user',{
            id: Number
        })
        .afterJSON(function(body) {
            next();
        });
}

function createProduct(){
    var product = fakeModels.product();

    return frisby.create('Get')
        .post('product?token='+config.token, product)
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('product' ,{
            id: Number
        })
        .afterJSON(function(body) {
            createdProduct= body.product;
            products.push(body.product);
            next();
        });
}

function updateAProduct(){
    var productName = faker.commerce.productName();
    return frisby.create('Update a Product')
        .put('product/'+createdProduct.id+'?token='+config.token,
            {
                description:productName
            })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('product' ,{
            id: Number,
            description: String
        })
        .expectJSON('product',{
            description : productName
        })
        .afterJSON(function(body) {
            next();
        });
}


function getADeletedProduct(){
    return frisby.create('Get a deleted Product')
        .get('product/'+createdProduct.id+'?token='+config.token,{
            json:false
        })
        .expectStatus(500)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('error' ,{
            exception: String
        })
        .afterJSON(function(body) {
            next();
        });
}


function DeleteProduct(){
    return frisby.create('delete Product')
        .delete('product/'+createdProduct.id+'?token='+config.token,{

        })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSON({
            success:true
        })
        .afterJSON(function(body) {
            next();
        });
}

function addProductsToInventory(){
    return frisby.create('Add Products to Inventory')
        .put('branch/1/inventory/product/'+products[0].id+'?token='+config.token, {
            quantity : faker.random.number(20)
        })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSON({
            success: true
        })
        .afterJSON(function(body) {
            next();
        });
}

function createABrand(){
    var brand = fakeModels.brand();

    return frisby.create('Create a Brand')
        .post('product/brand?token='+config.token, brand)
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('brand' ,{
            id: Number
        })
        .afterJSON(function(body) {
            createdBrand = body.brand;
            next();
        });
}

function updateABrand(){
    var brandName = faker.commerce.productMaterial();
    return frisby.create('Update a brand')
        .put('product/brand/'+createdBrand.id+'?token='+config.token,
            {
                name:brandName
            })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('brand' ,{
            id: Number,
            name: String
        })
        .expectJSON('brand',{
            name : brandName
        })
        .afterJSON(function(body) {
            next();
        });
}


function getADeletedBrand(){
    return frisby.create('Get a deleted Brand')
        .get('product/brand/'+createdBrand.id+'?token='+config.token,{
            json:false
        })
        .expectStatus(500)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('error' ,{
            exception: String
        })
        .afterJSON(function(body) {
            next();
        });
}


function DeleteBrand(){
    return frisby.create('delete Brand')
        .delete('product/brand/'+createdBrand.id+'?token='+config.token,{

        })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSON({
            success:true
        })
        .afterJSON(function(body) {
            next();
        });
}


function createACategory(){
    var category = fakeModels.category();

    return frisby.create('Create a Category')
        .post('product/category?token='+config.token, category)
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('category' ,{
            id: Number
        })
        .afterJSON(function(body) {
            createdCategory = body.category;
            next();
        });
    //.inspectJSON();
}

function getADeletedCategory(){
    return frisby.create('Get a deleted Category')
        .get('product/category/'+createdCategory.id+'?token='+config.token,{
            json:false
        })
        .expectStatus(500)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('error' ,{
            exception: String
        })
        .afterJSON(function(body) {
            next();
        });
}

function DeleteCategory(){
    return frisby.create('delete category')
        .delete('product/category/'+createdCategory.id+'?token='+config.token,{

        })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSON({
            success:true
        })
        .afterJSON(function(body) {
            next();
        });
}



function updateACategory(){
    var categoryName = faker.commerce.productMaterial();
    return frisby.create('Update a category')
        .put('product/category/'+createdCategory.id+'?token='+config.token,
            {
                name:categoryName
            })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('category' ,{
            id: Number,
            name: String
        })
        .expectJSON('category',{
            name : categoryName
        })
        .afterJSON(function(body) {
            next();
        });
}



function createAClient(){
    var client = fakeModels.client();

    return frisby.create('Create a Client')
        .post('client?token='+config.token, client)
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('client' ,{
            id: Number,
            name: String,
            last_name:String,
            last_name_2:String,
            email:String,
            phone:String,
            cellphone:String,
            address:String,
            rfc:String
        })
        .afterJSON(function(body) {
            createdClient = body.client;
            next();
        });
    //.inspectJSON();
}


function updateAClient(){
    var clientName = faker.name.firstName();
    return frisby.create('Update a Client')
        .put('client/'+createdClient.id+'?token='+config.token,
            {
                name:clientName
            })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('client' ,{
            id: Number,
            name: String
        })
        .expectJSON('client',{
            name : clientName
        })
        .afterJSON(function(body) {
            next();
        });
}



function getADeletedClient(){
    return frisby.create('Get a deleted Client')
        .get('client/'+createdClient.id+'?token='+config.token,{
            json:false
        })
        .expectStatus(500)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('error' ,{
            exception: String
        })
        .afterJSON(function(body) {
            next();
        });
}


function DeleteClient(){
    return frisby.create('delete client')
        .delete('client/'+createdClient.id+'?token='+config.token,{

        })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSON({
            success:true
        })
        .afterJSON(function(body) {
            next();
        });
}


function createASupplierCategory(){
    var category = fakeModels.category();

    return frisby.create('Create a Supplier Category')
        .post('supplier/category?token='+config.token, category)
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('category' ,{
            id: Number
        })
        .afterJSON(function(body) {
            createdSupplierCategory = body.category;
            next();
        });
    //.inspectJSON();
}

function getADeletedSupplierCategory(){
    return frisby.create('Get a deleted SupplierCategory')
        .get('supplier/category/'+createdSupplierCategory.id+'?token='+config.token,{
            json:false
        })
        .expectStatus(500)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('error' ,{
            exception: String
        })
        .afterJSON(function(body) {
            next();
        });
}

function deleteSupplierCategory(){
    return frisby.create('delete supplier category')
        .delete('supplier/category/'+createdSupplierCategory.id+'?token='+config.token,{

        })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSON({
            success:true
        })
        .afterJSON(function(body) {
            next();
        });
}


function updateASupplierCategory(){
    var categoryName = faker.commerce.productMaterial();
    return frisby.create('Update a supplier category')
        .put('supplier/category/'+createdSupplierCategory.id+'?token='+config.token,
            {
                name:categoryName
            })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('category' ,{
            id: Number,
            name: String
        })
        .expectJSON('category',{
            name : categoryName
        })
        .afterJSON(function(body) {
            next();
        });
}

function createASupplier(){
    var supplier = fakeModels.supplier();

    return frisby.create('Create a Supplier')
        .post('supplier?token='+config.token, supplier)
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('supplier' ,{
            id: Number
        })
        .afterJSON(function(body) {
            createdSupplier = body.supplier;
            next();
        });
    //.inspectJSON();
}

function getADeletedSupplier(){
    return frisby.create('Get a deleted Supplier')
        .get('supplier/'+createdSupplier.id+'?token='+config.token,{
            json:false
        })
        .expectStatus(500)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('error' ,{
            exception: String
        })
        .afterJSON(function(body) {
            next();
        });
}

function deleteSupplier(){
    return frisby.create('delete supplier')
        .delete('supplier/'+createdSupplier.id+'?token='+config.token,{

        })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSON({
            success:true
        })
        .afterJSON(function(body) {
            next();
        });
}


function updateASupplier(){
    var supplierName = faker.commerce.productMaterial();
    return frisby.create('Update a supplier ')
        .put('supplier/'+createdSupplier.id+'?token='+config.token,
            {
                name:supplierName
            })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('supplier' ,{
            id: Number,
            name: String
        })
        .expectJSON('supplier',{
            name : supplierName
        })
        .afterJSON(function(body) {
            next();
        });
}

//           FIn Supplier

function createARole(){
    var role = fakeModels.supplier();

    return frisby.create('Create a Role')
        .post('security?token='+config.token, role)
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('role' ,{
            id: Number
        })
        .afterJSON(function(body) {
            createdRole = body.role;
            next();
        });
    //.inspectJSON();
}

function getADeletedRole(){
    return frisby.create('Get a deleted Role')
        .get('security/'+createdRole.id+'?token='+config.token,{
            json:false
        })
        .expectStatus(500)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('error' ,{
            exception: String
        })
        .afterJSON(function(body) {
            next();
        });
}

function deleteRole(){
    return frisby.create('delete Role')
        .delete('security/'+createdRole.id+'?token='+config.token,{

        })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSON({
            success:true
        })
        .afterJSON(function(body) {
            next();
        });
}


function updateARole(){
    var roleName = faker.commerce.productMaterial();
    return frisby.create('Update a Role ')
        .put('security/'+createdRole.id+'?token='+config.token,
            {
                name:roleName
            })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('role' ,{
            id: Number,
            name: String
        })
        .expectJSON('role',{
            name : roleName
        })
        .afterJSON(function(body) {
            next();
        });
}


//FIN ROLE

function createABranchRole(){
    var branchRole = fakeModels.supplier();

    return frisby.create('Create a Branch Role')
        .post('branch?token='+config.token, branchRole)
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('branchRole' ,{
            id: Number
        })
        .afterJSON(function(body) {
            createdBranchRole = body.supplier;
            next();
        });
    //.inspectJSON();
}

function getADeleteBranchRole(){
    return frisby.create('Get a deleted Branch Role')
        .get('branch/'+createdBranchRole.id+'?token='+config.token,{
            json:false
        })
        .expectStatus(500)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('error' ,{
            exception: String
        })
        .afterJSON(function(body) {
            next();
        });
}

function deleteBranchRole(){
    return frisby.create('delete Branch Role')
        .delete('branch/'+createdBranchRole.id+'?token='+config.token,{

        })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSON({
            success:true
        })
        .afterJSON(function(body) {
            next();
        });
}


function updateABranchRole(){
    var branchRoleName = faker.commerce.productMaterial();
    return frisby.create('Update a Branch Role ')
        .put('branch/'+createdBranchRole.id+'?token='+config.token,
            {
                name:branchRoleName
            })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('branchRole' ,{
            id: Number,
            name: String
        })
        .expectJSON('branchRole',{
            name : branchRoleName
        })
        .afterJSON(function(body) {
            next();
        });
}
