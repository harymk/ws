const express = require('express');
//import { MongoClient } from "mongodb";
//require('dotenv').config()
//console.log(process.env.MONGODB_CONNECTION_STRING) // remove this after you've confirmed it working

const { MongoClient } = require("mongodb");
MONGODB_CONNECTION_STRING='mongodb+srv://harymk:Urmine143@smarthome.2gnasl6.mongodb.net/?retryWrites=true&w=majority'

const clien = new MongoClient(MONGODB_CONNECTION_STRING);  // remove this after you've confirmed it working


const app = express();
const http = require('http');
const serverPort = process.env.PORT || 3000;
const server = http.createServer(app);
const WebSocket = require("ws");

const wss =
  process.env.NODE_ENV === "production"
    ? new WebSocket.Server({ server })
    : new WebSocket.Server({ port: 5001 });



app.get('/', (req, res) => {

    res.sendFile(__dirname + '/index.html');
   
   });

   server.listen(serverPort);
   console.log(`Server started on port ${serverPort} in stage ${process.env.NODE_ENV}`);


async function run() {


  wss.on("connection", function (ws, req) {
    wed = ws;
    console.log("Connection Opened");
    console.log("Client size: ", wss.clients.size);
  
    if (wss.clients.size === 1) {
      console.log("first connection. starting keepalive");
      keepServerAlive();
    }
  
    ws.on("message", (data) => {
      let stringifiedData = data.toString();
      if (stringifiedData === 'pong') {
        console.log('keepAlive');
        return;
      }
      broadcast(ws, stringifiedData, false);
    });
  
    ws.on("close", (data) => {
      console.log("closing connection");
  
      if (wss.clients.size === 0) {
        console.log("last client disconnected, stopping keepAlive interval");
        clearInterval(keepAliveId);
      }
    });
  });
  
  // Implement broadcast function because of ws doesn't have it
  const broadcast = (ws, message, includeSelf) => {
    if (includeSelf) {
      wss.clients.forEach((client) => {
        if (client.readyState === WebSocket.OPEN) {
          client.send(message);
        }
      });
    } else {
      wss.clients.forEach((client) => {
        if (client !== ws && client.readyState === WebSocket.OPEN) {
          client.send(message);
        }
      });
    }
  };
  
  /**
   * Sends a ping message to all connected clients every 50 seconds
   */
   const keepServerAlive = () => {
    keepAliveId = setInterval(() => {
      wss.clients.forEach((client) => {
        if (client.readyState === WebSocket.OPEN) {
          client.send('ping');
        }
      });
    }, 50000);
  };





    try {
  
      await clien.connect();
      const database = clien.db('Device');
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
              let data = next.fullDocument;

              let stringifiedData = data.toString();
              
              broadcast(wed, JSON.stringify(data), false);

              

      }
  });
    } catch {
  
      // Ensures that the client will close when you error
      await clien.close();
    }
  }


  




app.get('/', (req, res) => {
  res.send('welcome');
});
  
  run().catch(console.dir);
