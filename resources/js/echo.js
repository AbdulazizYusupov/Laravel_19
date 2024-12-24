import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

console.log(chatId);

window.Echo.channel(`xabar.${chatId}`)
    .listen('MessageEvent', (e) => {
        console.log(e);
        const messageList = document.getElementById('messageList');
        const newMessage = document.createElement('li');
        newMessage.innerHTML = `<span class="text-primary">${e.sender}</span>: ${e.text}`;
        messageList.prepend(newMessage);
    });
