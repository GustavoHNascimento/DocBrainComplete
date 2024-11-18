// Função ChatGPT
document.addEventListener('DOMContentLoaded', function () {
    const chatContainer = document.getElementById('chat-container');
    const chatForm = document.getElementById('chat-form');
    const userInput = document.getElementById('user-input');

    chatForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const userMessage = userInput.value;
        appendMessage('Você:', userMessage);
        userInput.value = '';

        // Chame a função para interagir com o ChatGPT
        sendMessageToChatGPT(userMessage);
    });

    function appendMessage(sender, message) {
        const messageDiv = document.createElement('div');
        messageDiv.innerHTML = `<strong>${sender}</strong>: ${message}`;
        chatContainer.appendChild(messageDiv);
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    function sendMessageToChatGPT(userMessage) {
        // Substitua 'SUA_CHAVE_DE_API' pela sua chave de API do OpenAI
        const apiKey = 'SUA_CHAVE_DE_API';
        const apiUrl = 'https://api.openai.com/v1/engines/davinci-codex/completions';

        fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${apiKey}`,
            },
            body: JSON.stringify({
                prompt: userMessage,
                max_tokens: 150,
            }),
        })
        .then(response => response.json())
        .then(data => {
            const chatGPTResponse = data.choices[0].text.trim();
            appendMessage('ChatGPT:', chatGPTResponse);
        })
        .catch(error => console.error('Erro ao chamar a API do OpenAI:', error));
    }
});

function dropHandler(event) {
    event.preventDefault();
    const files = event.dataTransfer.files;
    handleFiles(files);
}

function dragOverHandler(event) {
    event.preventDefault();
}

function reprocessFile(button) {
    const row = button.closest('tr');
    const statusCell = row.cells[2];
    statusCell.textContent = 'Concluído';
}
