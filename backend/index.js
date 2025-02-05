const express = require("express");
const cors = require("cors");
const { createServer } = require("http");
const { Server } = require("socket.io");
const { MongoClient } = require("mongodb");
const bodyParser = require("body-parser");
require('dotenv').config();

const app = express();
app.use(
  cors({
    origin: ["http://localhost:8000", "http://localhost:8080"],
    credentials: true,
  })
);
app.use(bodyParser.json());

const uri = "mongodb://localhost:27017/";
const client = new MongoClient(uri);
let db;

async function connectToMongo() {
  try {
    await client.connect();
    db = client.db("admin");
    console.log("Connected to MongoDB");
  } catch (err) {
    console.error("Failed to connect to MongoDB", err);
  }
}

connectToMongo();

const server = createServer(app);
const io = new Server(server, {
  cors: {
    origin: "*",
    methods: ["GET", "POST"],
  },
});

io.on("connection", (socket) => {
  socket.on("username", (username) => {
    socket.username = username;
    console.log(`${username} connected`);
  });

  socket.on("joinRoom", async ({ sender, receiver }) => {
    const room = [sender, receiver].sort().join("_");
    console.log(`${sender} joined room ${room}`);
    socket.join(room);

    try {
      const messages = await db
        .collection("messages")
        .find({
          $or: [
            { sender, receiver },
            { sender: receiver, receiver: sender },
          ],
        })
        .sort({ timestamp: 1 })
        .toArray();
      console.log(messages);
      socket.emit("previousMessages", messages);
    } catch (err) {
      console.error("Error fetching messages:", err);
    }
  });

  socket.on("send-message", async ({ sender, receiver, message }) => {
    try {
      const room = [sender, receiver].sort().join("_");

      const chat = {
        sender,
        receiver,
        message,
        timestamp: new Date(),
      };

      console.log(chat);
      await db.collection("messages").insertOne(chat);

      io.to(room).emit("new-message", chat);
    } catch (err) {
      console.error("Failed to save/send message:", err);
    }
  });

  socket.on("disconnect", () => {
    console.log(`${socket.username} disconnected`);
  });
});

// Set default port if not defined
const PORT = process.env.PORT || 5000;
server.listen(PORT, () => {
  console.log(`Server running on port ${PORT}`);
});
