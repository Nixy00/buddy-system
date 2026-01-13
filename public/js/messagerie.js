function initMessagerie() {
    const form = document.getElementById('chat-form');
    const messagesContainer = document.getElementById('messages-container');
    const emptyMessage = document.getElementById('empty-message');

    // si on n'est pas sur la page de messagerie, on s'arrête
    if (!form || !messagesContainer) return;

    // sécurité anti-doublon
    if (form.dataset.initialized === "true") return;
    form.dataset.initialized = "true";

    // aller tout en bas à l'ouverture de la conversation (scroll)
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        const url = form.dataset.url;
        const formData = new FormData(form);

        fetch(url, {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (response.ok) {
                    return response.text();
                }
                throw new Error('Erreur réseau : ' + response.statusText);
            })
            .then(html => {
                if (emptyMessage) emptyMessage.remove();
                messagesContainer.insertAdjacentHTML('beforeend', html);
                const textarea = form.querySelector('textarea');
                if (textarea) textarea.value = '';
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            })
            .catch(error => {
                console.error('Erreur :', error);
                alert("Une erreur est survenue lors de l'envoi.");
            });
    });
}

document.addEventListener('turbo:load', initMessagerie);
document.addEventListener('DOMContentLoaded', initMessagerie);
