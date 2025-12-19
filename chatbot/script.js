function sendMessage() {
    const inputEl = document.getElementById('user-input');
    let userInput = inputEl.value;
    if (userInput.trim() === "") return;

    displayMessage(userInput, 'user');
    inputEl.value = "";
    inputEl.disabled = true;
    showTyping(true);

    fetch('./chatbot.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ message: userInput }),
    })
    .then(async response => {
        let json;
        try {
            json = await response.json();
        } catch (e) {
            throw new Error('Invalid JSON from server');
        }
        if (!response.ok) {
            const err = json.error || JSON.stringify(json);
            throw new Error(err);
        }
        if (json.reply) return json.reply;
        if (json.error) throw new Error(typeof json.error === 'string' ? json.error : JSON.stringify(json.error));
        throw new Error('No reply field in response');
    })
    .then(reply => {
        showTyping(false);
        displayMessage(reply, 'bot');
    })
    .catch(error => {
        showTyping(false);
        displayMessage('Error: ' + (error.message || error), 'bot');
        console.error('Chatbot error:', error);
    })
    .finally(() => {
        inputEl.disabled = false;
        inputEl.focus();
    });
}

function displayMessage(message, sender) {
    let chatBox = document.getElementById('chat-box');
    let messageDiv = document.createElement('div');
    messageDiv.classList.add('message', sender);
    messageDiv.innerText = message;
    chatBox.appendChild(messageDiv);
    chatBox.scrollTop = chatBox.scrollHeight;
}

function showTyping(show) {
    let chatBox = document.getElementById('chat-box');
    let typingId = 'bot-typing';
    let existing = document.getElementById(typingId);
    if (show) {
        if (existing) return;
        let typingDiv = document.createElement('div');
        typingDiv.id = typingId;
        typingDiv.classList.add('message', 'bot', 'typing');
        typingDiv.innerText = '...';
        chatBox.appendChild(typingDiv);
        chatBox.scrollTop = chatBox.scrollHeight;
    } else {
        if (existing) existing.remove();
    }
}

// Enter key sends message
document.addEventListener('DOMContentLoaded', function () {
    const inputEl = document.getElementById('user-input');
    if (inputEl) {
        inputEl.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    }
});
