
require('dotenv').config();

module.exports = {
    userCredentials: {
        username: 'admin',
        password: "admin2000"
    },
    token: null,
    url: process.env.APP_URL+"/api/v1/"
};
