<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EduHelper</title>

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .chat-box {
            background: white;
            width: 500px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .chat-header {
            background: #4a6cf7;
            color: white;
            padding: 14px 18px;
            font-size: 1rem;
            font-weight: bold;
        }

        .chat-header span {
            font-size: 0.75rem;
            font-weight: normal;
            opacity: 0.85;
            display: block;
            margin-top: 2px;
        }

        .chat-messages {
            height: 360px;
            overflow-y: auto;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: #f9f9f9;
        }

        .msg {
            max-width: 80%;
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 0.88rem;
            line-height: 1.5;
        }

        .msg.bot {
            background: #e8ecff;
            color: #333;
            align-self: flex-start;
            border-bottom-left-radius: 3px;
        }

        .msg.user {
            background: #4a6cf7;
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 3px;
        }

        .msg.typing {
            background: #e8ecff;
            color: #999;
            font-style: italic;
            align-self: flex-start;
        }

        .chat-footer {
            display: flex;
            border-top: 1px solid #eee;
            padding: 10px;
            gap: 8px;
            background: white;
        }

        .chat-footer input {
            flex: 1;
            padding: 9px 13px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.88rem;
            outline: none;
        }

        .chat-footer input:focus {
            border-color: #4a6cf7;
        }

        .chat-footer button {
            padding: 9px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
        }

        #sendBtn {
            background: #4a6cf7;
            color: white;
        }

        #resetBtn {
            background: #eee;
            color: #555;
        }
    </style>
</head>

<body>

    <div class="chat-box">

        <div class="chat-header">
            🎓 EduHelper
            <span>Topics: Solar System &nbsp;|&nbsp; Fractions &nbsp;|&nbsp; Water Cycle</span>
        </div>

        <div class="chat-messages" id="messages">
            <div class="msg bot">
                👋 Hi! I'm EduHelper. Ask me about <strong>Solar System</strong>,
                <strong>Fractions</strong>, or the <strong>Water Cycle</strong>!
            </div>
        </div>

        <div class="chat-footer">
            <input type="text" id="userInput" placeholder="Type your question..." autocomplete="off" />
            <button id="sendBtn">Send</button>
            <button id="resetBtn">Reset</button>
        </div>

    </div>

    <script>
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Auto-scroll to bottom
        function scrollDown() {
            var msgs = $('#messages');
            msgs.scrollTop(msgs[0].scrollHeight);
        }

        // Add a message bubble
        function addMessage(role, text) {
            var bubble = $('<div class="msg ' + role + '">' + text + '</div>');
            $('#messages').append(bubble);
            scrollDown();
            return bubble;
        }

        // Send message to backend
        function sendMessage() {
            var text = $('#userInput').val().trim();
            if (!text) return;

            addMessage('user', text);
            $('#userInput').val('');
            $('#sendBtn').prop('disabled', true);

            var typing = addMessage('typing', 'EduHelper is typing...');

            $.ajax({
                url: "{{ route('chat') }}",
                method: 'POST',
                contentType: 'application/json',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                data: JSON.stringify({ message: text }),
                success: function (res) {
                    typing.remove();
                    addMessage('bot', res.reply);
                },
                error: function () {
                    typing.remove();
                    addMessage('bot', '⚠️ Something went wrong. Please try again.');
                },
                complete: function () {
                    $('#sendBtn').prop('disabled', false);
                    $('#userInput').focus();
                }
            });
        }

        // Reset chat history
        function resetChat() {
            $.ajax({
                url: "{{ route('chat.reset') }}",
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                success: function () {
                    $('#messages').empty();
                    addMessage('bot', '🔄 Chat cleared! Ask me anything about Solar System, Fractions, or Water Cycle.');
                }
            });
        }

        // Event bindings
        $('#sendBtn').on('click', sendMessage);
        $('#resetBtn').on('click', resetChat);

        $('#userInput').on('keypress', function (e) {
            if (e.key === 'Enter') sendMessage();
        });
    </script>

</body>

</html>