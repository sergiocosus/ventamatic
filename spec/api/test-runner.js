module.exports = {
    tests : [],
    next : function() {
        var test = this.tests.shift();
        if(test){
            test().toss();
        }
    },
    clients: [],

    inventory: [],

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
