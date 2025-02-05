const Queue = require('bull');
const express = require("express");
const cors = require("cors");
const { createServer } = require("http");
const { Server } = require("socket.io");
const { MongoClient } = require("mongodb");
const bodyParser = require("body-parser");
require('dotenv').config();

const app = express();
app.use(cors());
app.use(bodyParser.json());

const queue = new Queue('mongoQueue');

// Job processor
queue.process(async (job) => {
    const { data } = job;
    const collection = await connectDb();
    await collection.insertOne(data);
    console.log('Data saved to MongoDB:', data);
});

// Function to connect to MongoDB
const connectDb = async () => {
    const uri = "mongodb://localhost:27017/chat";
    const client = new MongoClient(uri, {
        serverApi: {
            version: ServerApiVersion.v1,
            strict: true,
            deprecationErrors: true,
        }
    });

    await client.connect();
    const db = client.db("admin");
    const collection = db.collection("chatcollection");
    return collection;
};

// Start the server
const server = createServer(app);
const io = new Server(server);

server.listen(4000, () => {
    console.log('Server is running on port 4000');
});

module.exports = queue;
