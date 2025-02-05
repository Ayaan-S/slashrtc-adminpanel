<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <script src="<?= base_url('assets/js/jquery_min.js') ?>"></script>
    <script src="<?= base_url('assets/js/socketCDN.js') ?>"></script>
    <style>
        .chat-app {
        display: flex;
        height: 100vh;
        font-family: Arial, sans-serif;
    }

    .people-list {
        width: 250px;
        background-color: #f8f9fa;
        border-right: 1px solid #ddd;
        padding: 15px;
        overflow-y: auto;
    }

    .people-list .loggedUser  {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 15px;
        color: #333;
    }

    .people-list .chat-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .people-list .chat-list li {
        padding: 10px;
        margin-bottom: 5px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .people-list .chat-list li:hover {
        background-color: #e9ecef;
    }

    .chat {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .chat-header {
        padding: 15px;
        border-bottom: 1px solid #ddd;
        background-color: #f8f9fa;
    }

    .chat-header h6 {
        margin: 0;
        font-size: 16px;
        color: #333;
    }

    .chat-history {
        flex: 0.8;
        padding: 15px;
        overflow-y: auto;
        background-color: #fff;
    }

    .message {
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 8px;
        max-width: 70%;
        clear: both;
    }

    .sender-message {
        background-color: #007bff;
        color: white;
        float: right;
    }

    .receiver-message {
        background-color: #e9ecef;
        color: #333;
        float: left;
    }

    .chat-input {
        padding: 15px;
        border-top: 1px solid #ddd;
        background-color: #f8f9fa;
    }

    .chat-input input {
        width: 600px;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
    }

    .chat-input button {
        width: 70px;
        padding: 8px;
        margin-left: 10px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
    }

    .chat-input button:hover {
        background-color: #0056b3;
    }
    </style>
</head>
<body>
    <div class="chat-app">
        <div id="plist" class="people-list">
            <div class="loggedUser">Chats</div>
            <ul class="chat-list">
                <?php foreach ($users as $user) { ?>
                    <?php if ($user->name !== session('name')) { ?>
                        <li class="users" data-username="<?= $user->name ?>">
                            <div class="name"><?= $user->name ?></div>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </div>
        <div class="chat">
            <div class="chat-header">
                <h6 class="receiver">Select a user to chat</h6>
            </div>
            <div class="chat-history" id="messages">
                <!-- Messages shown here -->
            </div>
            <div class="chat-input">
                <input type="text" id="chat-input" placeholder="Enter message...">
                <button id="send-button">Send</button>
            </div>
        </div>
    </div>

    <script>
        const socket = io('http://localhost:5000');
        const username = '<?=  ucfirst(session()->get('user')->name)?>';
        let currentReceiver = null;

        socket.emit('username', username);

        document.querySelectorAll('.users').forEach(user => {
            user.addEventListener('click', () => {
                currentReceiver = user.dataset.username;
                document.querySelector('.receiver').textContent = currentReceiver;

                document.getElementById('messages').innerHTML = '';
                socket.emit('joinRoom', {
                    sender: username,
                    receiver: currentReceiver
                });
            });
        });

        function sendMessage() {
            const input = document.getElementById('chat-input');
            const message = input.value.trim();

            if (message && currentReceiver) {
                socket.emit('send-message', {
                    sender: username,
                    receiver: currentReceiver,
                    message: message
                });
                input.value = '';
            }
        }

        document.getElementById('send-button').addEventListener('click', sendMessage);

        document.getElementById('chat-input').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });

        socket.on('previousMessages', (messages) => {
            const chatHistory = document.getElementById('messages');
            chatHistory.innerHTML = '';

            messages.forEach(msg => {
                const messageDiv = document.createElement('div');
                messageDiv.className = `message ${msg.sender === username ? 'sender-message' : 'receiver-message'}`;
                messageDiv.textContent = msg.message;
                chatHistory.appendChild(messageDiv);
            });
            chatHistory.scrollTop = chatHistory.scrollHeight;
        });

        socket.on('new-message', (message) => {
            const chatHistory = document.getElementById('messages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${message.sender === username ? 'sender-message' : 'receiver-message'}`;
            messageDiv.textContent = message.message;
            chatHistory.appendChild(messageDiv);
            chatHistory.scrollTop = chatHistory.scrollHeight;
        });
    </script>
</body>
</html>



