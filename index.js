const dotenv = require('dotenv');
const express = require('express');
const cookieparser = require('cookie-parser');
const jwt = require('jsonwebtoken')
const bodyParser = require('body-parser');

// Configuring dotenv
dotenv.config();
const app = express();
const PORT = process.env.PORT || 3000;

// Setting up middlewares to parse request body and cookies
app.use(express.json());
app.use(express.urlencoded({ extended: false }));
app.use(cookieparser());

const cors = require('cors');
app.use(cors({
    origin: 'http://localhost:5173', // or your frontend domain
    credentials: true // allow cookies to be sent
}));


const userCredentials = {
    username: 'admin',
    password: 'admin123',
    email: 'admin@gmail.com'
}


app.post('/login', (req, res) => {
    // Destructuring username &amp; password from body
    const { username, password } = req.body;

    // Checking if credentials match
    if (username === userCredentials.username &&
        password === userCredentials.password) {

        //creating a access token
        const accessToken = jwt.sign({
            username: userCredentials.username,
            email: userCredentials.email
        }, process.env.ACCESS_TOKEN_SECRET, {
            expiresIn: '10m'
        });
        // Creating refresh token not that expiry of refresh 
        //token is greater than the access token

        const refreshToken = jwt.sign({
            username: userCredentials.username,
        }, process.env.REFRESH_TOKEN_SECRET, { expiresIn: '1d' });

        // Assigning refresh token in http-only cookie 
        res.cookie('jwt', refreshToken, {
            httpOnly: true,
            sameSite: 'None', secure: true,
            maxAge: 24 * 60 * 60 * 1000
        });
        return res.json({ accessToken });
    }
    else {
        // Return unauthorized error if credentials don't match
        return res.status(406).json({
            message: 'Invalid credentials'
        });
    }
})

app.post('/refresh', (req, res) => {
    if (req.cookies?.jwt) {

        // Destructuring refreshToken from cookie
        const refreshToken = req.cookies.jwt;

        // Verifying refresh token
        jwt.verify(refreshToken, process.env.REFRESH_TOKEN_SECRET,
            (err, decoded) => {
                if (err) {

                    // Wrong Refesh Token
                    return res.status(406).json({ message: 'Unauthorized' });
                }
                else {
                    // Correct token we send a new access token
                    const accessToken = jwt.sign({
                        username: userCredentials.username,
                        email: userCredentials.email
                    }, process.env.ACCESS_TOKEN_SECRET, {
                        expiresIn: '10m'
                    });
                    return res.json({ accessToken });
                }
            })
    } else {
        return res.status(406).json({ message: 'Unauthorized' });
    }
})

app.get('/protected', (req, res) => {
    const token = req.cookies.jwt;  // Retrieve the JWT from the cookie
    if (!token) {
        return res.status(403).json({ message: 'No token provided' });
    }

    jwt.verify(token, process.env.REFRESH_TOKEN_SECRET, (err, decoded) => {
        if (err) {
            return res.status(403).json({ message: 'Invalid token' });
        }
        // Proceed with the request if the token is valid
        res.status(200).json({ message: 'Protected content', user: decoded });
    });
});


app.post('/logout', (req, res) => {
    res.clearCookie('jwt', { httpOnly: true, sameSite: 'None', secure: true });
    res.sendStatus(204);
  });
  

app.get('/', ((req, res) => {
    res.send("Server");
    console.log("server running");
}))

app.listen(PORT, () => {
    console.log(`Server active on http://localhost:` + PORT);
})
