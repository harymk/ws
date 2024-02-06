const express = require('express');
//import { MongoClient } from "mongodb";
//require('dotenv').config()
//console.log(process.env.MONGODB_CONNECTION_STRING) // remove this after you've confirmed it working

const { MongoClient } = require("mongodb");
MONGODB_CONNECTION_STRING='mongodb+srv://harymk:Urmine143@smarthome.2gnasl6.mongodb.net/?retryWrites=true&w=majority'

const client = new MongoClient(MONGODB_CONNECTION_STRING);  // remove this after you've confirmed it working


const app = express();
const http = require('http');
const server = http.createServer(app);


app.get('/', (req, res) => {

    res.sendFile(__dirname + '/index.html');
   
   });

server.listen(3000, () => {
 console.log('listening on *:3000');
});

async function run() {

    try {
  
      await client.connect();
      const database = client.db('Device');
      const messages = database.collection('Status');
  
      // Query for our test message:
      //const query = { deviceid: "led" };
      //const message = await messages.findOne(query);
      //console.log(message);

     // changeStream = messages.watch();
      changeStream = messages.watch([], { fullDocument: 'updateLookup' });

  // set up a listener when change events are emitted
  changeStream.on("change", next => {
      // process any change event
      switch (next.operationType) {
          case 'insert':
              console.log(next.fullDocument.message);
              break;
          case 'update':
              //console.log(next.updateDescription.updatedFields);
              //console.log(next.documentKey._id);
              console.log(next.fullDocument);
      }
  });
  
    } catch {
  
      // Ensures that the client will close when you error
      await client.close();
    }
    

  }

  
  run().catch(console.dir);
