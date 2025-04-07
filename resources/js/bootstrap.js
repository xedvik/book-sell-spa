/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
import Pusher from 'pusher-js';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Pusher.logToConsole = false;

const pusher = new Pusher('8fb096f345a29c098f59', {
    cluster: 'eu',
    enabledTransports: ['ws', 'wss']
});

const booksChannel = pusher.subscribe('books');

booksChannel.bind('book.added', function(data) {
    showBookNotification(data);
});


function showBookNotification(book) {
    const notification = document.getElementById('book-notification');
    const bookTitle = document.getElementById('book-title');
    const bookPrice = document.getElementById('book-price');
    const bookCoverImg = document.getElementById('book-cover-img');
    const bookLink = document.getElementById('book-link');

    if (!notification || !bookTitle || !bookPrice || !bookCoverImg || !bookLink) {
        return;
    }


    bookTitle.textContent = book.title || 'Без названия';
    bookPrice.textContent = `${book.price || '0'} ₽`;


    if (book.cover_url) {
        bookCoverImg.src = book.cover_url;
        bookCoverImg.alt = book.title || 'Обложка книги';
        bookCoverImg.style.display = 'block';
    } else {
        bookCoverImg.style.display = 'none';
    }

    bookLink.href = `/books/${book.id}`;

    notification.classList.add('show');

    setTimeout(() => {
        notification.classList.remove('show');
    }, 9000);
}

window.showBookNotification = showBookNotification;

document.addEventListener('DOMContentLoaded', () => {
    const closeButton = document.getElementById('close-notification');
    const notification = document.getElementById('book-notification');

    if (closeButton && notification) {
        closeButton.addEventListener('click', () => {
            notification.classList.remove('show');
        });
    }
});


window.Pusher = Pusher;
window.pusherClient = pusher;
