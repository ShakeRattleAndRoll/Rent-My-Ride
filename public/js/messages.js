var rentRideMessages = window.rentRideMessages || (window.rentRideMessages = {});

function initMessages() {
    const chatContainer = document.getElementById("chat-container");
    const chatForm = document.getElementById("chat-form");

    if (rentRideMessages.pollTimer) {
        clearInterval(rentRideMessages.pollTimer);
        rentRideMessages.pollTimer = null;
    }

    if (rentRideMessages.abortController) {
        rentRideMessages.abortController.abort();
    }

    if (!chatContainer) return;

    rentRideMessages.abortController = new AbortController();

    const authId = Number(chatContainer.dataset.authId);
    const threadUrl = chatContainer.dataset.threadUrl;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    let lastRenderedIds = getRenderedMessageIds(chatContainer);

    const scrollToBottom = () => {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    };

    const renderMessages = (messages) => {
        const nextIds = messages.map((message) => String(message.id)).join(",");
        if (nextIds === lastRenderedIds) return;

        chatContainer.innerHTML = "";

        if (messages.length === 0) {
            chatContainer.innerHTML = `
                <div class="h-full flex items-center justify-center flex-col-reverse">
                    <p class="text-gray-600 text-xs uppercase font-black tracking-tighter">Start of conversation</p>
                </div>
            `;
            lastRenderedIds = nextIds;
            return;
        }

        [...messages].reverse().forEach((message) => {
            chatContainer.appendChild(createMessageElement(message, authId));
        });

        lastRenderedIds = nextIds;
        scrollToBottom();
    };

    const loadMessages = async () => {
        try {
            const response = await fetch(threadUrl, {
                headers: { Accept: "application/json" },
                signal: rentRideMessages.abortController.signal,
            });

            if (!response.ok) return;

            const data = await response.json();
            renderMessages(data.messages || []);
            window.refreshRentRideNotifications?.();
        } catch (error) {
            if (error.name !== "AbortError") {
                console.error("Unable to refresh messages.", error);
            }
        }
    };

    scrollToBottom();
    loadMessages();
    rentRideMessages.pollTimer = setInterval(loadMessages, 2000);

    if (chatForm && !chatForm.dataset.ajaxReady) {
        chatForm.dataset.ajaxReady = "true";
        chatForm.addEventListener("submit", async (event) => {
            event.preventDefault();

            const input = chatForm.querySelector("#message_body");
            const submitButton = chatForm.querySelector('button[type="submit"]');
            const body = input.value.trim();

            if (!body) return;

            submitButton.disabled = true;

            try {
                const response = await fetch(chatForm.action, {
                    method: "POST",
                    headers: {
                        Accept: "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: new FormData(chatForm),
                });

                if (response.ok) {
                    input.value = "";
                    await loadMessages();
                    window.refreshRentRideNotifications?.();
                }
            } finally {
                submitButton.disabled = false;
                input.focus();
            }
        });
    }

    window.addEventListener("scroll-chat", () => {
        setTimeout(scrollToBottom, 100);
    }, { once: true });
}

function getRenderedMessageIds(container) {
    return [...container.querySelectorAll("[data-message-id]")]
        .map((element) => element.dataset.messageId)
        .join(",");
}

function createMessageElement(message, authId) {
    const isMine = Number(message.sender_id) === authId;
    const wrapper = document.createElement("div");
    wrapper.dataset.messageId = message.id;
    wrapper.className = `flex flex-col ${isMine ? "items-end ml-auto" : "items-start"} max-w-[70%] mt-6`;

    const bubble = document.createElement("div");
    bubble.className = `p-4 rounded-2xl shadow-md ${isMine ? "bg-yellow-400 text-black rounded-tr-none" : "bg-[#242424] text-gray-300 rounded-tl-none border border-white/5"}`;

    const body = document.createElement("p");
    body.className = "text-sm leading-relaxed";
    body.textContent = message.body;

    const time = document.createElement("span");
    time.className = "text-[9px] text-gray-600 font-bold mt-2 uppercase tracking-widest";
    time.textContent = message.time;

    bubble.appendChild(body);
    wrapper.appendChild(bubble);
    wrapper.appendChild(time);

    return wrapper;
}

if (!rentRideMessages.bound) {
    rentRideMessages.bound = true;
    document.addEventListener("DOMContentLoaded", initMessages);
    document.addEventListener("livewire:navigated", initMessages);
}

initMessages();
