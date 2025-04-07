import Pusher from 'pusher-js';

export default {
    mounted() {
        if (!import.meta.env.VITE_PUSHER_APP_KEY || !import.meta.env.VITE_PUSHER_APP_CLUSTER) {
            return;
        }

        if (window.Echo) {
            this.setupEchoListeners();
        } else {
            this.setupDirectPusherConnection();
        }

        document.addEventListener('DOMContentLoaded', () => {
            const closeButton = document.getElementById('close-notification');
            const notification = document.getElementById('book-notification');

            if (closeButton) {
                closeButton.addEventListener('click', this.hideNotification);
            }
        });
    },

    methods: {
        // Настройка слушателей при использовании Laravel Echo
        setupEchoListeners() {
            try {
                window.Echo.channel('books')
                    .listen('book.added', (data) => {
                        this.handleBookEvent(data);
                    })
            } catch (error) {
                // Обработка ошибок
            }
        },

        // Прямое подключение к Pusher (если Echo недоступен)
        setupDirectPusherConnection() {
            try {
                Pusher.logToConsole = false;

                const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
                    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
                    wsHost: import.meta.env.VITE_PUSHER_HOST || 'api-eu.pusher.com',
                    wsPort: parseInt(import.meta.env.VITE_PUSHER_PORT) || 443,
                    wssPort: parseInt(import.meta.env.VITE_PUSHER_PORT) || 443,
                    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME || 'https') === 'https',
                    enabledTransports: ['ws', 'wss']
                });

                const channel = pusher.subscribe('books');
                channel.bind('book.added', (data) => {
                    this.handleBookEvent(data);
                });

                channel.bind('book_added', (data) => {
                    this.handleBookEvent(data);
                });

                channel.bind('.book.added', (data) => {
                    this.handleBookEvent(data);
                });

                channel.bind('BookAdded', (data) => {
                    this.handleBookEvent(data);
                });

                channel.bind_global((eventName, data) => {
                    if (eventName.toLowerCase().includes('book') &&
                        (eventName.toLowerCase().includes('add') || eventName.toLowerCase().includes('new'))) {
                        this.handleBookEvent(data);
                    }
                });
            } catch (error) {
                // Обработка ошибок
            }
        },

        handleBookEvent(data) {
            if (typeof window.showBookNotification === 'function') {
                window.showBookNotification(data);
            } else if (typeof showBookNotification === 'function') {
                showBookNotification(data);
            }
            this.dispatchCustomEvent(data);
        },

        dispatchCustomEvent(data) {
            const event = new CustomEvent('new-book', { detail: data });
            document.dispatchEvent(event);
        },

        hideNotification() {
            const notification = document.getElementById('book-notification');
            if (notification) {
                notification.classList.remove('show');
            }
        }
    }
}
