document.addEventListener('DOMContentLoaded', () => {
    // elements du DOM
    const toggler = document.getElementById('chatbot-toggler');
    const chatWindow = document.getElementById('chatbot-window');
    const closeBtn = document.getElementById('chatbot-close');
    const inputField = document.getElementById('chatbot-input');
    const messagesContainer = document.getElementById('chatbot-messages');

    // gestion ouverture/fermeture
    function toggleChat() {
        chatWindow.classList.toggle('hidden');
        if (!chatWindow.classList.contains('hidden')) {
            inputField.focus();
        }
    }

    toggler.addEventListener('click', toggleChat);
    closeBtn.addEventListener('click', () => chatWindow.classList.add('hidden'));

    // fonctions d'affichage
    function addMessageToUI(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message', sender);

        const contentDiv = document.createElement('div');
        contentDiv.classList.add('message-content');
        contentDiv.innerHTML = text;

        messageDiv.appendChild(contentDiv);
        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
});
