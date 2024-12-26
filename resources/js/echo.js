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
console.log(userId);

window.Echo.channel(`xabar.${chatId}`)
    .listen('MessageEvent', (e) => {
        console.log('Received Event Data:', e);
        console.log('File URL:', e.file);

        const messageList = document.getElementById('messageList');
        const newMessage = document.createElement('li');

        let filePreview = '';

        if (e.file) {
            const extension = e.file.split('.').pop().toLowerCase();
            if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
                filePreview = `<img src="${e.file}" alt="Image" width="200px" style="margin-top: 5px;">`;
            } else if (['mp4', 'webm', 'ogg'].includes(extension)) {
                filePreview = `
                    <video width="200px" controls style="margin-top: 5px;">
                        <source src="${e.file}" type="video/${extension}">
                        Your browser does not support the video tag.
                    </video>`;
            } else if (['mp3'].includes(extension)) {
                filePreview = `
                    <audio controls style="margin-top: 5px;">
                        <source src="${e.file}" type="audio/${extension}">
                        Your browser does not support the audio tag.
                    </audio>`;
            } else if (['txt', 'pdf', 'doc', 'docx'].includes(extension)) {
                filePreview = `<a href="${e.file}" target="_blank" style="color: #007bff; font-weight: bold; margin-top: 5px;">Download File</a>`;
            } else {
                filePreview = `<a href="${e.file}" target="_blank" style="color: #007bff; font-weight: bold; margin-top: 5px;">Download File</a>`;
            }
        }

        newMessage.innerHTML = `
            <li style="padding: 10px; border-bottom: 1px solid #e0e0e0">
                <span class="text-primary" style="font-weight: bold">${e.sender}:</span>
                <span>${e.text}</span>
                <div class="file-preview">${filePreview}</div>
            </li>
        `;

        if (e.sender !== userId) {
            messageList.prepend(newMessage);
        }
    });

window.Echo.channel('newUser')
    .listen('NewUserEvent', (e) => {
        console.log('New user registered:', e);
        const userList = document.getElementById('userList');
        const newUser = document.createElement('li');
        newUser.classList.add('list-group-item');
        const userProfileUrl = `/show/${e.id}`;
        newUser.innerHTML = `<a href="${userProfileUrl}">${e.name}</a>`;
        userList.prepend(newUser);
    }); 

