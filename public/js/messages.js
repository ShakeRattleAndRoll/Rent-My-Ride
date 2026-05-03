document.addEventListener("DOMContentLoaded", function () {
    const chatForm = document.getElementById('chat-form');
    const chatContainer = document.getElementById("chat-container");

    if (!chatForm || !chatContainer) return;

    chatContainer.scrollTop = chatContainer.scrollHeight;

    const messageInput = document.getElementById('message_body');
    const sendBtn = chatForm.querySelector('button[type="submit"]');

    sendBtn.disabled = true;
    sendBtn.classList.add('opacity-50', 'cursor-default');

    messageInput.addEventListener('input', () => {
        const isEmpty = messageInput.value.trim() === '';
        sendBtn.disabled = isEmpty;
        sendBtn.classList.toggle('opacity-50', isEmpty);
        sendBtn.classList.toggle('cursor-default', isEmpty);
    });

    chatForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const bodyInput = document.getElementById('message_body');
        const receiverId = document.getElementById('receiver_id');
        const body = bodyInput.value.trim();

        if (!body) return;

        const newMessage = `
            <div class="flex flex-col items-end ml-auto max-w-[70%]">
                <div class="p-4 rounded-2xl shadow-md bg-yellow-400 text-black rounded-tr-none">
                    <p class="text-sm leading-relaxed">${body}</p>
                </div>
                <span class="text-[9px] text-gray-600 font-bold mt-2 uppercase tracking-widest">Just now</span>
            </div>
        `;

        chatContainer.insertAdjacentHTML('beforeend', newMessage);
        chatContainer.scrollTop = chatContainer.scrollHeight;
        bodyInput.value = '';

        sendBtn.disabled = true;
        sendBtn.classList.add('opacity-50', 'cursor-default');

        fetch("/messages", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify({
                receiver_id: receiverId.value,
                body: body
            })
        });
    });
});