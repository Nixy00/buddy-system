document.addEventListener('DOMContentLoaded', () => {
    // elements du DOM
    const toggler = document.getElementById('chatbot-toggler');
    const chatWindow = document.getElementById('chatbot-window');
    const closeBtn = document.getElementById('chatbot-close');
    const inputField = document.getElementById('chatbot-input');

    // gestion ouverture/fermeture
    function toggleChat() {
        chatWindow.classList.toggle('hidden');
        if (!chatWindow.classList.contains('hidden')) {
            inputField.focus();
        }
    }

    toggler.addEventListener('click', toggleChat);
    closeBtn.addEventListener('click', () => chatWindow.classList.add('hidden'));
});
