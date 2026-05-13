var rentRideMessages = window.rentRideMessages || (window.rentRideMessages = {});

function initMessages() {
    const chatContainer = document.getElementById("chat-container");
    const chatComposer = document.getElementById("chat-composer");

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

    const blockedHtml = () => `
        <div class="p-4 bg-black/20 text-center text-red-500 text-xs font-bold uppercase" data-chat-blocked-notice>
            <i class="fa-solid fa-ban mr-2"></i> You cannot send messages in this conversation.
        </div>
    `;

    const formHtml = () => `
        <div class="p-3 lg:p-6 bg-[#1a1a1a] border-t border-white/5" data-chat-form-wrap>
            <form id="chat-form" action="${chatComposer.dataset.storeUrl}" method="POST" class="flex items-center gap-2 lg:gap-3">
                <input type="hidden" id="receiver_id" name="receiver_id" value="${chatComposer.dataset.receiverId}">
                <input type="text" id="message_body" name="body" placeholder="Write your message..."
                    class="min-w-0 flex-1 bg-[#242424] text-white px-4 py-3 lg:px-6 lg:py-3.5 rounded-2xl border border-white/5 outline-none focus:border-lime-400 transition-all font-medium text-sm">
                <button type="submit" class="w-11 h-11 shrink-0 bg-lime-400 text-black rounded-2xl flex items-center justify-center shadow-lg shadow-lime-400/20 hover:bg-lime-300 transition">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>
    `;

    const bindChatForm = () => {
        const chatForm = document.getElementById("chat-form");
        if (!chatForm || chatForm.dataset.ajaxReady) return;

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
                } else if (response.status === 403) {
                    renderComposer(true);
                }
            } finally {
                submitButton.disabled = false;
                input.focus();
            }
        });
    };

    const renderComposer = (isBlocked) => {
        if (!chatComposer) return;

        const nextBlocked = isBlocked ? "1" : "0";
        const hasMatchingState = chatComposer.dataset.blocked === nextBlocked;
        const hasExpectedMarkup = isBlocked
            ? Boolean(chatComposer.querySelector("[data-chat-blocked-notice]"))
            : Boolean(chatComposer.querySelector("#chat-form"));

        if (hasMatchingState && hasExpectedMarkup) return;

        chatComposer.dataset.blocked = nextBlocked;
        chatComposer.innerHTML = isBlocked ? blockedHtml() : formHtml();

        if (!isBlocked) {
            bindChatForm();
        }
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
            renderComposer(Boolean(data.chat_blocked));
            window.refreshRentRideNotifications?.();
        } catch (error) {
            if (error.name !== "AbortError") {
                console.error("Unable to refresh messages.", error);
            }
        }
    };

    scrollToBottom();
    renderComposer(chatComposer?.dataset.blocked === "1");
    bindChatForm();
    loadMessages();
    rentRideMessages.pollTimer = setInterval(loadMessages, 2000);

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
    wrapper.className = `flex min-w-0 flex-col ${isMine ? "items-end ml-auto" : "items-start"} max-w-[84%] lg:max-w-[70%] mt-4 lg:mt-6`;

    const bubble = document.createElement("div");
    bubble.className = `max-w-full px-4 py-3 lg:p-4 rounded-2xl shadow-md ${isMine ? "bg-lime-400 text-black rounded-tr-none" : "bg-[#242424] text-gray-300 rounded-tl-none border border-white/5"}`;

    const body = document.createElement("p");
    body.className = "text-sm leading-relaxed break-words [overflow-wrap:anywhere]";
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
