
require('dotenv').config();

module.exports = {
    userCredentials: {
        grant_type: 'password',
        username: 'admin',
        password: "admin2000",
        client_id: process.env.TEST_CLIENT_ID,
        client_secret: process.env.TEST_CLIENT_SECRET
    },
    token: null,
    url: process.env.APP_URL+"/api/v1/",
    urlLogin: process.env.APP_URL+"/oauth/token/"
};
