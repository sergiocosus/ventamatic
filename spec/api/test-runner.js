module.exports = {
    tests : [],
    next : function() {
        var test = this.tests.shift();
        if(test){
            var preparedTest = test();
            if(preparedTest){
                preparedTest.toss();
            }
        }
    },
    clients: [],

    inventories: [],

    createdClient:null,

    createdSupplier:null,

    total_sale:null,

    products: [],
    lastProduct : function(){
        if(this.products.length)
            return this.products[this.products.length-1];
        else
            return null;
    },
    deletedProducts: [],
    lastDeletedProduct: function (){
        if(this.deletedProducts.length)
            return this.deletedProducts[this.deletedProducts.length-1];
        else
            return null;
    },
    deleteProduct: function () {
        this.deletedProducts.push(this.products.pop());
    }
};
