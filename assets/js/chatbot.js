/* =============================================================
   KL Travel Concierge Chatbot — assets/js/chatbot.js
   ============================================================= */
(function () {
    'use strict';

    // ── Config ──────────────────────────────────────────────────
    var API_ENDPOINT = 'chatbot-api.php';
    var BOT_AVATAR   = 'asset-backups/chatbook.jpg';
    var STORAGE_KEY  = 'kltg_chat_pos';
    var QUICK_ACTIONS = [
        { label: '🗺️ Plan My Trip',    msg: 'I want to plan a trip to KL. Can you help me create an itinerary?' },
        { label: '🍜 Where To Eat',    msg: 'What are the best places to eat in KL?' },
        { label: '🏨 Where To Stay',   msg: 'What are the best hotels and accommodation options in KL?' },
        { label: '🛍️ Shopping',        msg: 'Where are the best places to shop in Kuala Lumpur?' },
        { label: '💆 Spa & Wellness',  msg: 'Can you recommend spas and wellness centres in KL?' },
        { label: '🏥 Medical Tourism', msg: 'Tell me about medical tourism options in KL.' },
    ];

    // ── State ────────────────────────────────────────────────────
    var history    = [];
    var isOpen     = false;
    var isLoading  = false;
    var hasGreeted = false;
    var isDragging = false;
    var dragOffX   = 0;
    var dragOffY   = 0;

    // ── Build DOM ────────────────────────────────────────────────
    var widget = document.createElement('div');
    widget.id  = 'kltg-chat-widget';
    widget.innerHTML =
        '<span id="kltg-chat-cta" aria-hidden="true">Chat Now!</span>' +
        '<button id="kltg-chat-btn" aria-label="Open KL Travel Concierge chat" title="Drag to move · Click to chat">' +
            '<canvas id="kltg-book-canvas" width="68" height="68" aria-hidden="true"></canvas>' +
            '<span id="kltg-chat-badge" aria-label="1 new message">1</span>' +
        '</button>';

    // Remove white background from chatbook.jpg by processing pixels on canvas
    function loadBookWithTransparentBg(src, canvas) {
        var img = new Image();
        img.onload = function () {
            var W = img.naturalWidth  || 68;
            var H = img.naturalHeight || 68;
            canvas.width  = W;
            canvas.height = H;
            var ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, W, H);

            var data = ctx.getImageData(0, 0, W, H);
            var px   = data.data;

            // Sample top-left 4×4 region for the background colour
            var bgR = 0, bgG = 0, bgB = 0, n = 0;
            for (var sy = 0; sy < 4; sy++) {
                for (var sx = 0; sx < 4; sx++) {
                    var idx = (sy * W + sx) * 4;
                    bgR += px[idx]; bgG += px[idx + 1]; bgB += px[idx + 2];
                    n++;
                }
            }
            bgR = Math.round(bgR / n);
            bgG = Math.round(bgG / n);
            bgB = Math.round(bgB / n);

            var HARD = 35;   // px distance → fully transparent
            var SOFT = 70;   // px distance → fully opaque

            for (var i = 0; i < px.length; i += 4) {
                var dr = px[i]     - bgR;
                var dg = px[i + 1] - bgG;
                var db = px[i + 2] - bgB;
                var dist = Math.sqrt(dr * dr + dg * dg + db * db);

                if (dist < HARD) {
                    px[i + 3] = 0;
                } else if (dist < SOFT) {
                    px[i + 3] = Math.round(((dist - HARD) / (SOFT - HARD)) * 255);
                }
            }

            ctx.putImageData(data, 0, 0);
        };
        img.onerror = function () {
            // Fallback: draw a simple book emoji if image fails
            var ctx = canvas.getContext('2d');
            ctx.font = '44px serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText('📚', canvas.width / 2, canvas.height / 2);
        };
        img.src = src;
    }

    var popup = document.createElement('div');
    popup.id  = 'kltg-chat-popup';
    popup.setAttribute('role', 'dialog');
    popup.setAttribute('aria-label', 'KL Travel Concierge chat');
    popup.innerHTML =
        '<div id="kltg-chat-header">' +
            '<img id="kltg-chat-header-avatar" src="' + BOT_AVATAR + '" alt="KL Travel Concierge">' +
            '<div id="kltg-chat-header-info">' +
                '<h4>KL Travel Concierge</h4>' +
                '<span>AI-powered travel assistant</span>' +
            '</div>' +
            '<span id="kltg-chat-header-status" title="Online"></span>' +
            '<button id="kltg-chat-close" aria-label="Close chat">&times;</button>' +
        '</div>' +
        '<div id="kltg-chat-messages" role="log" aria-live="polite"></div>' +
        '<div id="kltg-quick-actions-bar"></div>' +
        '<div id="kltg-chat-input-area">' +
            '<textarea id="kltg-chat-input" rows="1" placeholder="Ask me anything about KL..." maxlength="500"></textarea>' +
            '<button id="kltg-chat-send" aria-label="Send message">' +
                '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>' +
            '</button>' +
        '</div>' +
        '<div id="kltg-chat-powered">Powered by <a href="https://deepmind.google/technologies/gemini/" target="_blank" rel="noopener">Google Gemini</a></div>';

    document.body.appendChild(widget);
    document.body.appendChild(popup);

    // Process chatbook.jpg → transparent background on canvas
    var bookCanvas = document.getElementById('kltg-book-canvas');
    if (bookCanvas) loadBookWithTransparentBg(BOT_AVATAR, bookCanvas);

    // Build persistent quick-actions bar
    var quickBar = document.getElementById('kltg-quick-actions-bar');
    QUICK_ACTIONS.forEach(function (a) {
        var b = document.createElement('button');
        b.className = 'kltg-quick-btn';
        b.textContent = a.label;
        b.addEventListener('click', function () { sendMessage(a.msg); });
        quickBar.appendChild(b);
    });

    // ── Element refs ─────────────────────────────────────────────
    var btn      = document.getElementById('kltg-chat-btn');
    var closeBtn = document.getElementById('kltg-chat-close');
    var messages = document.getElementById('kltg-chat-messages');
    var input    = document.getElementById('kltg-chat-input');
    var sendBtn  = document.getElementById('kltg-chat-send');
    var badge    = document.getElementById('kltg-chat-badge');

    // ── Widget positioning ────────────────────────────────────────
    function clampPos(x, y) {
        var pad    = 8;
        var W      = window.innerWidth;
        var H      = window.innerHeight;
        var bw     = widget.offsetWidth  || 68;
        var bh     = widget.offsetHeight || 68;
        var header = document.getElementById('header');
        var topMin = pad + (header ? header.offsetHeight : 70);
        return {
            x: Math.max(pad, Math.min(W - bw - pad, x)),
            y: Math.max(topMin, Math.min(H - bh - pad, y))
        };
    }

    function setWidgetPos(x, y) {
        // Switch from bottom/right to top/left so we can place it freely
        widget.style.bottom = 'auto';
        widget.style.right  = 'auto';
        widget.style.left   = x + 'px';
        widget.style.top    = y + 'px';
    }

    function savePos(x, y) {
        try { localStorage.setItem(STORAGE_KEY, JSON.stringify({ x: x, y: y })); } catch (e) {}
    }

    function loadPos() {
        try {
            var p = JSON.parse(localStorage.getItem(STORAGE_KEY) || 'null');
            if (p && typeof p.x === 'number' && typeof p.y === 'number') return p;
        } catch (e) {}
        return null;
    }

    // Apply saved position (re-clamp on load in case header height changed)
    var saved = loadPos();
    if (saved) {
        // Defer until DOM is painted so header.offsetHeight is accurate
        window.addEventListener('load', function () {
            var c = clampPos(saved.x, saved.y);
            setWidgetPos(c.x, c.y);
            savePos(c.x, c.y);
        });
    }
    // If no saved pos, the CSS bottom/right defaults hold.

    // ── Popup positioning ─────────────────────────────────────────
    function positionPopup() {
        var W    = window.innerWidth;
        var H    = window.innerHeight;
        var pw   = popup.offsetWidth  || 380;
        var ph   = popup.offsetHeight || 540;
        var rect = widget.getBoundingClientRect();
        var GAP  = 12;

        // Prefer above; fall back to below
        var top  = rect.top - ph - GAP;
        if (top < 8) top = rect.bottom + GAP;

        // Prefer right-aligned with widget; clamp to viewport
        var left = rect.right - pw;
        left = Math.max(8, Math.min(W - pw - 8, left));

        popup.style.top  = top  + 'px';
        popup.style.left = left + 'px';
        popup.style.bottom = 'auto';
        popup.style.right  = 'auto';
    }

    // ── Drag logic ───────────────────────────────────────────────
    var clickThreshold = 5;   // px of movement before we call it a drag
    var dragStartX = 0, dragStartY = 0;
    var didDrag = false;

    function onDragStart(clientX, clientY) {
        var rect = widget.getBoundingClientRect();
        dragOffX  = clientX - rect.left;
        dragOffY  = clientY - rect.top;
        dragStartX = clientX;
        dragStartY = clientY;
        didDrag   = false;
        isDragging = true;
        widget.classList.add('dragging');
        btn.style.cursor = 'grabbing';
    }

    function onDragMove(clientX, clientY) {
        if (!isDragging) return;

        if (!didDrag) {
            var dx = clientX - dragStartX;
            var dy = clientY - dragStartY;
            if (Math.sqrt(dx * dx + dy * dy) < clickThreshold) return;
            didDrag = true;
        }

        var raw = { x: clientX - dragOffX, y: clientY - dragOffY };
        var pos = clampPos(raw.x, raw.y);
        setWidgetPos(pos.x, pos.y);

        if (isOpen) positionPopup();
    }

    function onDragEnd(clientX, clientY) {
        if (!isDragging) return;
        isDragging = false;
        widget.classList.remove('dragging');
        btn.style.cursor = '';

        if (didDrag) {
            var pos = clampPos(clientX - dragOffX, clientY - dragOffY);
            savePos(pos.x, pos.y);
        }
    }

    // Mouse events
    btn.addEventListener('mousedown', function (e) {
        if (e.button !== 0) return;
        e.preventDefault();
        onDragStart(e.clientX, e.clientY);
    });

    document.addEventListener('mousemove', function (e) {
        onDragMove(e.clientX, e.clientY);
    });

    document.addEventListener('mouseup', function (e) {
        if (!isDragging) return;
        var wasDrag = didDrag;
        onDragEnd(e.clientX, e.clientY);
        // If it was a click (not a drag), toggle chat
        if (!wasDrag) {
            isOpen ? closeChat() : openChat();
        }
    });

    // Touch events
    btn.addEventListener('touchstart', function (e) {
        e.preventDefault();
        var t = e.touches[0];
        onDragStart(t.clientX, t.clientY);
    }, { passive: false });

    document.addEventListener('touchmove', function (e) {
        if (!isDragging) return;
        e.preventDefault();
        var t = e.touches[0];
        onDragMove(t.clientX, t.clientY);
    }, { passive: false });

    document.addEventListener('touchend', function (e) {
        if (!isDragging) return;
        var t = e.changedTouches[0];
        var wasDrag = didDrag;
        onDragEnd(t.clientX, t.clientY);
        if (!wasDrag) {
            isOpen ? closeChat() : openChat();
        }
    });

    // ── Markdown → HTML (lightweight) ───────────────────────────
    function mdToHtml(text) {
        return text
            .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
            .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.+?)\*/g,     '<em>$1</em>')
            .replace(/\[([^\]]+)\]\((https?:\/\/[^\)]+)\)/g,
                     '<a href="$2" target="_blank" rel="noopener">$1</a>')
            .replace(/^#{3,4}\s+(.+)$/gm, '<h4>$1</h4>')
            .replace(/^[\-\*]\s+(.+)$/gm, '<li>$1</li>')
            .replace(/(<li>.*<\/li>)/s, function(m) { return '<ul>' + m + '</ul>'; })
            .replace(/^\d+\.\s+(.+)$/gm, '<li>$1</li>')
            .replace(/\n{2,}/g, '</p><p>')
            .replace(/\n/g, '<br>');
    }

    function wrapContent(html) { return '<p>' + html + '</p>'; }

    // ── Append messages ──────────────────────────────────────────
    function appendMessage(role, text, isHtml) {
        var div = document.createElement('div');
        div.className = 'kltg-msg ' + role;

        var avatar = document.createElement('img');
        avatar.className = 'kltg-msg-avatar';
        if (role === 'bot') {
            avatar.src = BOT_AVATAR;
            avatar.alt = 'KL Travel Concierge';
        } else {
            avatar.src = 'assets/img/user-avatar.svg';
            avatar.alt = 'You';
            avatar.onerror = function () {
                var circle = document.createElement('div');
                circle.className = 'kltg-msg-avatar';
                circle.style.cssText = 'background:#0ea2bd;color:#fff;font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;border-radius:50%;';
                circle.textContent = 'Y';
                div.replaceChild(circle, this);
            };
        }

        var bubble = document.createElement('div');
        bubble.className = 'kltg-msg-bubble';
        if (isHtml) { bubble.innerHTML = wrapContent(mdToHtml(text)); }
        else         { bubble.textContent = text; }

        if (role === 'bot') { div.appendChild(avatar); div.appendChild(bubble); }
        else                { div.appendChild(bubble); div.appendChild(avatar); }

        messages.appendChild(div);
        scrollToBottom();
        return div;
    }

    function appendTyping() {
        var wrap = document.createElement('div');
        wrap.className = 'kltg-msg bot';
        wrap.id = 'kltg-typing-indicator';
        var avatar = document.createElement('img');
        avatar.className = 'kltg-msg-avatar';
        avatar.src = BOT_AVATAR;
        avatar.alt = '';
        var dots = document.createElement('div');
        dots.className = 'kltg-typing';
        dots.innerHTML = '<span></span><span></span><span></span>';
        wrap.appendChild(avatar);
        wrap.appendChild(dots);
        messages.appendChild(wrap);
        scrollToBottom();
    }

    function removeTyping() {
        var t = document.getElementById('kltg-typing-indicator');
        if (t) t.remove();
    }

    function scrollToBottom() { messages.scrollTop = messages.scrollHeight; }

    // ── Greeting ─────────────────────────────────────────────────
    function showGreeting() {
        if (hasGreeted) return;
        hasGreeted = true;
        appendMessage('bot',
            'Selamat datang! 👋 I\'m your **KL Travel Concierge**.\n\nWhether you\'re planning a trip, hunting for the best food, or looking for hidden gems in Kuala Lumpur — I\'ve got you covered. Pick a topic below or type your own question!',
            true);
    }

    // ── Open / Close ─────────────────────────────────────────────
    function openChat() {
        isOpen = true;
        widget.classList.add('open');
        positionPopup();
        popup.classList.add('visible');
        if (badge) badge.style.display = 'none';
        showGreeting();
        setTimeout(function () { input.focus(); }, 300);
    }

    function closeChat() {
        isOpen = false;
        widget.classList.remove('open');
        popup.classList.remove('visible');
    }

    closeBtn.addEventListener('click', closeChat);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && isOpen) closeChat(); });

    // Reposition popup on window resize
    window.addEventListener('resize', function () { if (isOpen) positionPopup(); });

    // ── Send message ─────────────────────────────────────────────
    function sendMessage(text) {
        text = (text || input.value).trim();
        if (!text || isLoading) return;

        input.value = '';
        input.style.height = 'auto';
        sendBtn.disabled = true;
        isLoading = true;

        appendMessage('user', text, false);
        appendTyping();
        history.push({ role: 'user', text: text });

        fetch(API_ENDPOINT, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: text, history: history.slice(0, -1) }),
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            removeTyping();
            if (data.error) {
                appendMessage('bot', '⚠️ Sorry, something went wrong: ' + data.error, false);
                history.pop();
            } else {
                appendMessage('bot', data.reply, true);
                history.push({ role: 'model', text: data.reply });
            }
        })
        .catch(function () {
            removeTyping();
            appendMessage('bot', '⚠️ Network error. Please check your connection and try again.', false);
            history.pop();
        })
        .finally(function () {
            isLoading = false;
            sendBtn.disabled = false;
            input.focus();
        });
    }

    sendBtn.addEventListener('click', function () { sendMessage(); });

    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
    });

    input.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 100) + 'px';
    });

    // ── Public API: let other page elements open/close the chat ──
    window.kltgOpenChat   = function () { if (!isOpen) openChat(); };
    window.kltgToggleChat = function () { isOpen ? closeChat() : openChat(); };

}());
