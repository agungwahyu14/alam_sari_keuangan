<!-- Chatbot Widget dengan Alpine.js -->
<div x-data="chatbotWidget()" x-init="init()" class="chatbot-widget-container">
    <!-- Floating Button -->
    <button @click="toggleChat" class="chatbot-floating-btn" :class="{ 'active': isOpen }" type="button">
        <svg x-show="!isOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
            stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
        </svg>
        <svg x-show="isOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
            stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <!-- Chat Window -->
    <div x-show="isOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90" class="chatbot-window" style="display: none;">

        <!-- Header -->
        <div class="chatbot-header">
            <div class="flex items-center">
                <div class="chatbot-avatar">🤖</div>
                <div>
                    <h3 class="chatbot-title">Asisten Onboarding</h3>
                    <p class="chatbot-subtitle">Siap membantu Anda</p>
                </div>
            </div>
            <button @click="toggleChat" class="chatbot-close-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Messages Area -->
        <div class="chatbot-messages" x-ref="messagesContainer">
            <!-- Welcome Message -->
            <template x-if="currentAnswer === null">
                <div>
                    <div class="message bot-message">
                        <div class="message-avatar">🤖</div>
                        <div class="message-content">
                            <p>Halo! Saya asisten virtual Anda. Pilih pertanyaan di bawah untuk mendapatkan jawaban! 😊
                            </p>
                        </div>
                    </div>

                    <!-- All Questions as Buttons -->
                    <div class="questions-list">
                        <p class="text-xs text-gray-600 mb-3 font-semibold px-2">📋 Pilih Pertanyaan:</p>
                        <template x-for="(faq, index) in allQuestions" :key="faq.id">
                            <button @click="showAnswer(faq)" class="question-item-btn"
                                x-text="(index + 1) + '. ' + faq.question">
                            </button>
                        </template>
                    </div>
                </div>
            </template>

            <!-- Answer Display -->
            <template x-if="currentAnswer !== null">
                <div>
                    <!-- User Question -->
                    <div class="message user-message">
                        <div class="message-avatar">👤</div>
                        <div class="message-content">
                            <p x-text="currentAnswer.question"></p>
                        </div>
                    </div>

                    <!-- Bot Answer -->
                    <div class="message bot-message">
                        <div class="message-avatar">🤖</div>
                        <div class="message-content">
                            <p x-text="currentAnswer.answer"></p>
                        </div>
                    </div>

                    <!-- Back Button -->
                    <div class="px-4 mt-3">
                        <button @click="backToQuestions" class="back-to-questions-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-4 h-4 inline mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                            </svg>
                            Kembali ke Daftar Pertanyaan
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<style>
    .chatbot-widget-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
    }

    .chatbot-floating-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .chatbot-floating-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.5);
    }

    .chatbot-floating-btn.active {
        transform: rotate(90deg);
    }

    .chatbot-window {
        position: fixed;
        bottom: 90px;
        right: 20px;
        width: 380px;
        height: 550px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .chatbot-header {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        color: white;
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chatbot-avatar {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-right: 12px;
    }

    .chatbot-title {
        font-size: 16px;
        font-weight: 700;
        margin: 0;
    }

    .chatbot-subtitle {
        font-size: 12px;
        margin: 0;
        opacity: 0.9;
    }

    .chatbot-close-btn {
        background: transparent;
        border: none;
        color: white;
        cursor: pointer;
        padding: 4px;
        transition: opacity 0.2s;
    }

    .chatbot-close-btn:hover {
        opacity: 0.8;
    }

    .chatbot-messages {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
        background: #f9fafb;
    }

    .message {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .bot-message .message-avatar {
        background: #e0f2fe;
    }

    .user-message {
        flex-direction: row-reverse;
    }

    .user-message .message-avatar {
        background: #dbeafe;
    }

    .message-content {
        max-width: 75%;
        padding: 10px 14px;
        border-radius: 12px;
        font-size: 14px;
        line-height: 1.5;
    }

    .bot-message .message-content {
        background: white;
        border: 1px solid #e5e7eb;
    }

    .user-message .message-content {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .message-time {
        display: block;
        font-size: 10px;
        margin-top: 4px;
        opacity: 0.7;
    }

    .typing-indicator {
        display: flex;
        gap: 4px;
        padding: 12px 14px;
    }

    .typing-indicator span {
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        animation: typing 1.4s infinite;
    }

    .typing-indicator span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-indicator span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typing {

        0%,
        60%,
        100% {
            transform: translateY(0);
            opacity: 0.5;
        }

        30% {
            transform: translateY(-10px);
            opacity: 1;
        }
    }

    .questions-list {
        padding: 12px;
        max-height: 400px;
        overflow-y: auto;
    }

    .question-item-btn {
        display: block;
        width: 100%;
        text-align: left;
        padding: 12px 14px;
        margin-bottom: 10px;
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border: 2px solid #bbf7d0;
        border-radius: 10px;
        font-size: 13px;
        color: #047857;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .question-item-btn:hover {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        border-color: #86efac;
        transform: translateX(5px);
        box-shadow: 0 4px 8px rgba(16, 185, 129, 0.2);
    }

    .question-item-btn:active {
        transform: translateX(3px) scale(0.98);
    }

    .question-item-btn:last-child {
        margin-bottom: 0;
    }

    .back-to-questions-btn {
        width: 100%;
        padding: 10px 16px;
        background: #f3f4f6;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 13px;
        color: #374151;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .back-to-questions-btn:hover {
        background: #e5e7eb;
        border-color: #9ca3af;
    }

    @media (max-width: 480px) {
        .chatbot-window {
            width: calc(100vw - 40px);
            height: calc(100vh - 120px);
            bottom: 90px;
            right: 20px;
        }
    }
</style>

<script>
    function chatbotWidget() {
        return {
            isOpen: false,
            currentAnswer: null,
            allQuestions: @json($allQuestions),

            init() {
                // Initialize component
            },

            toggleChat() {
                this.isOpen = !this.isOpen;
                // Reset to questions list when closing
                if (!this.isOpen) {
                    setTimeout(() => {
                        this.currentAnswer = null;
                    }, 300);
                }
                if (this.isOpen) {
                    this.$nextTick(() => {
                        this.scrollToBottom();
                    });
                }
            },

            showAnswer(faq) {
                this.currentAnswer = {
                    question: faq.question,
                    answer: faq.answer
                };
                this.$nextTick(() => {
                    this.scrollToBottom();
                });
            },

            backToQuestions() {
                this.currentAnswer = null;
                this.$nextTick(() => {
                    this.scrollToBottom();
                });
            },

            scrollToBottom() {
                this.$nextTick(() => {
                    const container = this.$refs.messagesContainer;
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                });
            }
        }
    }
</script>
