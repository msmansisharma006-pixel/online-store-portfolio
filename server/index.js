const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const cors = require('cors');

const app = express();
app.use(cors());

const server = http.createServer(app);
const io = new Server(server, {
    cors: {
        origin: "*",
        methods: ["GET", "POST"]
    }
});

// Real-time WebSocket connection handling
io.on('connection', (socket) => {
    console.log(`User connected: ${socket.id}`);

    // Listen for incoming customer messages
    socket.on('send_message', (data) => {
        // Broadcast the message to all connected clients (e.g., support agents)
        io.emit('receive_message', data);
    });

    socket.on('disconnect', () => {
        console.log(`User disconnected: ${socket.id}`);
    });
});

const PORT = 5000;
server.listen(PORT, () => {
    console.log(`Node.js Live Chat server running on http://localhost:${PORT}`);
});