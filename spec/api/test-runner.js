module.exports = {
    tests : [],
    next : function() {
        var test = this.tests.shift();
        if(test){
            test().toss();
        }
    }
};
