document.addEventListener('DOMContentLoaded', () => {
    // elements du DOM
    const toggler = document.getElementById('chatbot-toggler');
    const chatWindow = document.getElementById('chatbot-window');
    const closeBtn = document.getElementById('chatbot-close');
    const inputField = document.getElementById('chatbot-input');
    const messagesContainer = document.getElementById('chatbot-messages');
    const sendBtn = document.getElementById('chatbot-send-btn');

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

    function addSuggestionsToUI(suggestions) {
        const suggestionsDiv = document.createElement('div');
        suggestionsDiv.classList.add('message', 'bot', 'suggestions-container');
        suggestionsDiv.style.flexDirection = 'row';
        suggestionsDiv.style.flexWrap = 'wrap';
        suggestionsDiv.style.gap = '5px';
        suggestionsDiv.style.background = 'transparent';

        suggestions.forEach(suggestion => {
            const chip = document.createElement('button');
            chip.textContent = suggestion;
            chip.classList.add('suggestion-chip');

            chip.addEventListener('click', () => sendMessage(suggestion));

            suggestionsDiv.appendChild(chip);
        });

        messagesContainer.appendChild(suggestionsDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function removeSuggestions() {
        const existingSuggestions = document.querySelectorAll('.suggestions-container');
        existingSuggestions.forEach(el => el.remove());
    }

    function sendMessage(text = null) {
        const message = text || inputField.value.trim();

        if (!message) return;

        // affichage message user
        addMessageToUI(message, 'user');
        inputField.value = '';
        removeSuggestions();

        // appel API avec fetch et promesses
        fetch('/api/chatbot', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ message: message }),
            credentials: 'include'
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('erreur reseau : ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                addMessageToUI(data.response, 'bot');

                if (data.suggestions && data.suggestions.length > 0) {
                    addSuggestionsToUI(data.suggestions);
                }

                if (data.action_url) {
                    setTimeout(() => {
                        window.location.href = data.action_url;
                    }, 2000);
                }
            })
            .catch(error => {
                // gestion des erreurs
                console.error('Erreur fetch :', error);
                addMessageToUI("oups ! erreur de connexion.", 'bot');
            });
    }

    // listeners envoi
    sendBtn.addEventListener('click', () => sendMessage());

    inputField.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

});
