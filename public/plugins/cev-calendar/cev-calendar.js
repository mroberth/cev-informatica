class CevCalendar {
    constructor(container, options = {}) {
        this.container = typeof container === 'string'
            ? document.querySelector(container)
            : container;

        this.options = {
            events: [],
            locale: 'es',
            firstDayOfWeek: 1,
            dayNames: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                         'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            onDayClick: null,
            onEventClick: null,
            ...options,
        };

        this.currentDate = options.date ? new Date(options.date) : new Date();
        this.selectedDate = null;
        this.events = [...(this.options.events || [])];
        this.eventIdCounter = this.events.length;
        this._detailEl = null;

        this.container.classList.add('cev-calendar');
        this.render();
    }

    /* ─── RENDER ─── */
    render() {
        this.container.innerHTML = '';
        this.container.appendChild(this._createHeader());
        this.container.appendChild(this._createWeekdays());
        this.container.appendChild(this._createDays());
        this._detailEl = this._createDetail();
        this.container.appendChild(this._detailEl);
        this._updateDetail();
    }

    /* ─── HEADER ─── */
    _createHeader() {
        const header = document.createElement('div');
        header.className = 'cev-calendar-header';

        const prev = document.createElement('button');
        prev.className = 'cev-calendar-nav cev-calendar-prev';
        prev.innerHTML = '<i class="bi bi-chevron-left"></i>';
        prev.addEventListener('click', () => this.prevMonth());

        const title = document.createElement('h3');
        title.className = 'cev-calendar-title';
        title.textContent = `${this.options.monthNames[this.currentDate.getMonth()]} ${this.currentDate.getFullYear()}`;

        const next = document.createElement('button');
        next.className = 'cev-calendar-nav cev-calendar-next';
        next.innerHTML = '<i class="bi bi-chevron-right"></i>';
        next.addEventListener('click', () => this.nextMonth());

        const today = document.createElement('button');
        today.className = 'cev-calendar-today';
        today.textContent = 'Hoy';
        today.addEventListener('click', () => this.goToToday());

        header.append(prev, title, next, today);
        return header;
    }

    /* ─── WEEKDAY HEADERS ─── */
    _createWeekdays() {
        const wd = document.createElement('div');
        wd.className = 'cev-calendar-weekdays';
        const names = this._getOrderedDayNames();
        names.forEach(n => {
            const el = document.createElement('div');
            el.textContent = n;
            wd.appendChild(el);
        });
        return wd;
    }

    _getOrderedDayNames() {
        const fow = this.options.firstDayOfWeek;
        const names = this.options.dayNames;
        return [...names.slice(fow), ...names.slice(0, fow)];
    }

    /* ─── DAYS GRID ─── */
    _createDays() {
        const grid = document.createElement('div');
        grid.className = 'cev-calendar-days';

        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const daysInPrev = new Date(year, month, 0).getDate();
        const fow = this.options.firstDayOfWeek;
        const startOffset = (firstDay - fow + 7) % 7;

        const today = new Date();
        const todayStr = this._dateStr(today);

        // Previous month days
        for (let i = startOffset - 1; i >= 0; i--) {
            grid.appendChild(this._createDayCell(daysInPrev - i, year, month - 1, true));
        }

        // Current month days
        for (let d = 1; d <= daysInMonth; d++) {
            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
            const isToday = dateStr === todayStr;
            const dayOfWeek = new Date(year, month, d).getDay();
            const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;
            grid.appendChild(this._createDayCell(d, year, month, false, isToday, isWeekend, dateStr));
        }

        // Next month days (to fill 7 columns)
        const totalCells = grid.children.length;
        const remaining = (7 - (totalCells % 7)) % 7;
        for (let d = 1; d <= remaining; d++) {
            grid.appendChild(this._createDayCell(d, year, month + 1, true));
        }

        return grid;
    }

    _createDayCell(day, year, month, isOther, isToday = false, isWeekend = false, dateStr = '') {
        const cell = document.createElement('div');
        cell.className = 'cev-calendar-day';
        if (isOther) cell.classList.add('cev-calendar-day-other');
        if (isWeekend && !isOther) cell.classList.add('cev-calendar-day-weekend');
        if (isToday) cell.classList.add('cev-calendar-day-today');

        const num = document.createElement('div');
        num.className = 'cev-calendar-day-number';
        num.textContent = day;
        cell.appendChild(num);

        if (dateStr) {
            cell.dataset.date = dateStr;

            const dayEvents = this._getEventsForDate(dateStr);
            if (dayEvents.length > 0) {
                const eventsEl = document.createElement('div');
                eventsEl.className = 'cev-calendar-events';

                const maxVisible = 3;
                const visible = dayEvents.slice(0, maxVisible);
                visible.forEach(ev => {
                    const dot = document.createElement('span');
                    dot.className = 'cev-calendar-event-dot';
                    dot.style.background = ev.color || this._getDefaultEventColor();
                    dot.title = ev.title || '';
                    dot.addEventListener('click', (e) => {
                        e.stopPropagation();
                        if (this.options.onEventClick) this.options.onEventClick(ev);
                    });
                    eventsEl.appendChild(dot);
                });

                if (dayEvents.length > maxVisible) {
                    const more = document.createElement('span');
                    more.className = 'cev-calendar-event-more';
                    more.textContent = `+${dayEvents.length - maxVisible}`;
                    more.addEventListener('click', (e) => {
                        e.stopPropagation();
                        this.selectedDate = dateStr;
                        this._updateDetail();
                    });
                    eventsEl.appendChild(more);
                }

                cell.appendChild(eventsEl);
            }

            cell.addEventListener('click', () => {
                this.selectedDate = dateStr;
                this._clearSelected();
                cell.classList.add('cev-calendar-day-selected');
                this._updateDetail();
                if (this.options.onDayClick) this.options.onDayClick(dateStr, dayEvents);
            });

            if (dateStr === this.selectedDate) {
                cell.classList.add('cev-calendar-day-selected');
            }
        }

        return cell;
    }

    _clearSelected() {
        this.container.querySelectorAll('.cev-calendar-day-selected').forEach(el => {
            el.classList.remove('cev-calendar-day-selected');
        });
    }

    /* ─── DETAIL PANEL ─── */
    _createDetail() {
        const el = document.createElement('div');
        el.className = 'cev-calendar-detail';
        return el;
    }

    _updateDetail() {
        if (!this._detailEl) return;

        if (!this.selectedDate) {
            this._detailEl.innerHTML = '<div class="cev-calendar-detail-empty">Selecciona un día para ver los eventos.</div>';
            return;
        }

        const events = this._getEventsForDate(this.selectedDate);
        const dateObj = new Date(this.selectedDate + 'T12:00:00');
        const dayName = this.options.dayNames[dateObj.getDay()];
        const monthName = this.options.monthNames[dateObj.getMonth()];
        const dayNum = dateObj.getDate();

        let html = `<div class="cev-calendar-detail-header">
            <i class="bi bi-calendar-event me-1"></i> ${dayName}, ${dayNum} de ${monthName}
        </div>`;

        if (events.length === 0) {
            html += '<div class="cev-calendar-detail-empty">Sin eventos para este día.</div>';
        } else {
            events.forEach(ev => {
                const color = ev.color || this._getDefaultEventColor();
                html += `<div class="cev-calendar-detail-item" data-event-id="${ev.id}">
                    <span class="cev-calendar-detail-dot" style="background:${color}"></span>
                    <span><strong>${this._escapeHtml(ev.title)}</strong></span>
                    ${ev.time ? `<span class="cev-calendar-detail-time">${this._escapeHtml(ev.time)}</span>` : ''}
                </div>`;
            });
        }

        this._detailEl.innerHTML = html;

        this._detailEl.querySelectorAll('.cev-calendar-detail-item').forEach(el => {
            el.addEventListener('click', () => {
                const id = el.dataset.eventId;
                const ev = this.events.find(e => String(e.id) === id);
                if (ev && this.options.onEventClick) this.options.onEventClick(ev);
            });
        });
    }

    /* ─── NAVIGATION ─── */
    prevMonth() {
        this.currentDate.setMonth(this.currentDate.getMonth() - 1);
        this.selectedDate = null;
        this.render();
    }

    nextMonth() {
        this.currentDate.setMonth(this.currentDate.getMonth() + 1);
        this.selectedDate = null;
        this.render();
    }

    goToToday() {
        this.currentDate = new Date();
        const todayStr = this._dateStr(this.currentDate);
        this.selectedDate = todayStr;
        this.render();
        this._scrollToToday();
    }

    goToDate(date) {
        this.currentDate = typeof date === 'string' ? new Date(date + 'T12:00:00') : new Date(date);
        this.selectedDate = typeof date === 'string' ? date : this._dateStr(date);
        this.render();
    }

    _scrollToToday() {
        const todayEl = this.container.querySelector('.cev-calendar-day-today');
        if (todayEl) todayEl.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
    }

    /* ─── EVENT MANAGEMENT ─── */
    addEvent(event) {
        const ev = {
            id: ++this.eventIdCounter,
            date: '',
            title: 'Evento',
            description: '',
            color: this._getDefaultEventColor(),
            time: '',
            ...event,
        };
        this.events.push(ev);
        this.render();
        return ev;
    }

    removeEvent(id) {
        this.events = this.events.filter(e => e.id !== id);
        this.render();
    }

    updateEvent(id, updates) {
        const idx = this.events.findIndex(e => e.id === id);
        if (idx !== -1) {
            this.events[idx] = { ...this.events[idx], ...updates };
            this.render();
        }
    }

    setEvents(events) {
        this.events = [...events];
        this.render();
    }

    getEvents() {
        return [...this.events];
    }

    getEventsForDate(dateStr) {
        return this._getEventsForDate(dateStr);
    }

    _getEventsForDate(dateStr) {
        return this.events.filter(e => e.date === dateStr);
    }

    /* ─── HELPERS ─── */
    _dateStr(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    _getDefaultEventColor() {
        const colors = ['#0d6efd', '#198754', '#dc3545', '#ffc107', '#0dcaf0', '#6f42c1', '#fd7e14'];
        return colors[Math.floor(Math.random() * colors.length)];
    }

    _escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    destroy() {
        this.container.innerHTML = '';
        this.container.classList.remove('cev-calendar');
    }
}

window.CevCalendar = CevCalendar;
