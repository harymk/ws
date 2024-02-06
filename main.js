import { WebSocketServer } from 'ws';

const wss = new WebSocketServer({ port: 8080 });



wss.on("connection", function (ws, req) {
    console.log("Connection Opened");
    console.log("Client size: ", wss.clients.size);
  
    if (wss.clients.size === 1) {
      console.log("first connection. starting keepalive");
      //keepServerAlive();
    }
  
    ws.on("message", (data) => {
      let stringifiedData = data.toString();
      if (stringifiedData === 'pong') {
        console.log('keepAlive');
        return;
      }
      //broadcast(ws, stringifiedData, false);
    });
  
    ws.on("close", (data) => {
      console.log("closing connection");
  
      if (wss.clients.size === 0) {
        console.log("last client disconnected, stopping keepAlive interval");
       // clearInterval(keepAliveId);
      }
    });
  });
