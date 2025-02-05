const { MongoClient, ServerApiVersion } = require("mongodb");

const uri = "mongodb://localhost:27017/chat";

const client = new MongoClient(uri,  {
        serverApi: {
            version: ServerApiVersion.v1,
            strict: true,
            deprecationErrors: true,
        }
    }
);

const connectDb = async () => {
  try {
    await client.connect();
    await client.db("admin").command({ ping: 1 });
    console.log("You successfully connected to MongoDB!");
    const db = client.db("admin");
    const collection = db.collection("messages");
    return collection;
    // return db;
  } finally {
    await client.close();
  }
}

module.exports = connectDb;