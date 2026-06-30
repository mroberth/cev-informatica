class CevCalendar {
    constructor(container, options = {}) {
        this.container = typeof container === 'string'
            ? document.querySelector(container)
            : container;

        if (!this.container) {
            throw new Error('CevCalendar: no se encontro el contenedor indicado.');
        }

        this.options = {
            events: [],
            locale: 'es-VE',
            firstDayOfWeek: 1,
            dayNames: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
            views: ['dayGridMonth', 'listMonth'],
            initialView: 'dayGridMonth',
            labels: {
                today: 'Hoy',
                dayGridMonth: 'Mes',
                listMonth: 'Lista',
                emptyDay: 'Sin eventos para este dia.',
                emptyList: 'No hay eventos en este mes.',
                hint: 'Selecciona un dia para ver sus eventos.'
            },
            onDayClick: null,
            onEventClick: null,
            ...options,
        };

        this.currentDate = this._normalizeDate(options.date || new Date());
        this.selectedDate = options.selectedDate || null;
        this.currentView = this.options.views.includes(this.options.initialView)
            ? this.options.initialView
            : this.options.views[0] || 'dayGridMonth';

        this.events = this._normalizeEvents(this.options.events || []);
        this.eventIdCounter = this.events.reduce((max, ev) => {
            const id = Number(ev.id);
            return Number.isFinite(id) ? Math.max(max, id) : max;
        }, 0);

        this.container.classList.add('cev-calendar');
        this.render();
    }

    render() {
        this.container.innerHTML = '';
        this.container.appendChild(this._renderToolbar());

        if (this.currentView === 'listMonth') {
            this.container.appendChild(this._renderListView());
            return;
        }

        const grid = document.createElement('section');
        grid.className = 'cev-calendar-grid';
        grid.appendChild(this._renderWeekdays());
        grid.appendChild(this._renderDays());
        this.container.appendChild(grid);
        this.container.appendChild(this._renderDayDetail());
    }

    _renderToolbar() {
        const toolbar = document.createElement('header');
        toolbar.className = 'cev-calendar-toolbar';

        const left = document.createElement('div');
        left.className = 'cev-calendar-toolbar-group';

        const btnPrev = this._createButton('<i class="bi bi-chevron-left"></i>', () => this.prev());
        const btnNext = this._createButton('<i class="bi bi-chevron-right"></i>', () => this.next());
        const btnToday = this._createButton(this.options.labels.today, () => this.goToToday());

        left.append(btnPrev, btnNext, btnToday);

        const title = document.createElement('h2');
        title.className = 'cev-calendar-title';
        title.textContent = this._formatMonthTitle(this.currentDate);

        const right = document.createElement('div');
        right.className = 'cev-calendar-toolbar-group';
        this.options.views.forEach((viewName) => {
            const label = this.options.labels[viewName] || viewName;
            const btn = this._createButton(label, () => this.changeView(viewName));
            if (viewName === this.currentView) {
                btn.classList.add('is-active');
            }
            right.appendChild(btn);
        });

        toolbar.append(left, title, right);
        return toolbar;
    }

    _renderWeekdays() {
        const row = document.createElement('div');
        row.className = 'cev-calendar-weekdays';
        this._getOrderedDayNames().forEach((name) => {
            const item = document.createElement('div');
            item.className = 'cev-calendar-weekday';
            item.textContent = name;
            row.appendChild(item);
        });
        return row;
    }

    _renderDays() {
        const daysGrid = document.createElement('div');
        daysGrid.className = 'cev-calendar-days';

        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const daysInPrevMonth = new Date(year, month, 0).getDate();
        const fow = this.options.firstDayOfWeek;
        const leadingCells = (firstDay - fow + 7) % 7;

        for (let i = leadingCells - 1; i >= 0; i -= 1) {
            daysGrid.appendChild(this._renderDayCell({
                day: daysInPrevMonth - i,
                dateStr: '',
                isOtherMonth: true,
            }));
        }

        const todayStr = this._dateStr(new Date());
        for (let day = 1; day <= daysInMonth; day += 1) {
            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            daysGrid.appendChild(this._renderDayCell({
                day,
                dateStr,
                isToday: dateStr === todayStr,
                isSelected: dateStr === this.selectedDate,
                events: this._getEventsForDate(dateStr),
            }));
        }

        const usedCells = daysGrid.children.length;
        const trailingCells = (7 - (usedCells % 7)) % 7;
        for (let day = 1; day <= trailingCells; day += 1) {
            daysGrid.appendChild(this._renderDayCell({
                day,
                dateStr: '',
                isOtherMonth: true,
            }));
        }

        return daysGrid;
    }

    _renderDayCell({ day, dateStr, isToday = false, isSelected = false, isOtherMonth = false, events = [] }) {
        const cell = document.createElement('article');
        cell.className = 'cev-calendar-day';

        if (isOtherMonth) {
            cell.classList.add('cev-calendar-day-other');
        }
        if (isToday) {
            cell.classList.add('cev-calendar-day-today');
        }
        if (isSelected) {
            cell.classList.add('cev-calendar-day-selected');
        }

        const number = document.createElement('span');
        number.className = 'cev-calendar-day-number';
        number.textContent = day;
        cell.appendChild(number);

        if (!dateStr) {
            return cell;
        }

        cell.dataset.date = dateStr;
        const eventsWrap = document.createElement('div');
        eventsWrap.className = 'cev-calendar-events';

        const maxVisible = 2;
        events.slice(0, maxVisible).forEach((event) => {
            const chip = document.createElement('button');
            chip.type = 'button';
            chip.className = 'cev-calendar-event-chip';
            chip.style.backgroundColor = event.color || this._getDefaultEventColor();
            chip.textContent = event.title || 'Evento';
            chip.title = `${event.time ? `${event.time} - ` : ''}${event.title || 'Evento'}`;
            chip.addEventListener('click', (ev) => {
                ev.stopPropagation();
                this._emitEventClick(event);
            });
            eventsWrap.appendChild(chip);
        });

        if (events.length > maxVisible) {
            const moreBtn = document.createElement('button');
            moreBtn.type = 'button';
            moreBtn.className = 'cev-calendar-event-more';
            moreBtn.textContent = `+${events.length - maxVisible} mas`;
            moreBtn.addEventListener('click', (ev) => {
                ev.stopPropagation();
                this.selectedDate = dateStr;
                this.render();
            });
            eventsWrap.appendChild(moreBtn);
        }

        cell.appendChild(eventsWrap);

        cell.addEventListener('click', () => {
            this.selectedDate = dateStr;
            const dayEvents = this._getEventsForDate(dateStr);
            if (typeof this.options.onDayClick === 'function') {
                this.options.onDayClick(dateStr, dayEvents);
            }
            this.render();
        });

        return cell;
    }

    _renderDayDetail() {
        const section = document.createElement('section');
        section.className = 'cev-calendar-detail';

        if (!this.selectedDate) {
            section.innerHTML = `
                <h3 class="cev-calendar-detail-title">Detalle del dia</h3>
                <p class="cev-calendar-empty">${this._escapeHtml(this.options.labels.hint)}</p>
            `;
            return section;
        }

        const events = this._getEventsForDate(this.selectedDate);
        section.innerHTML = `<h3 class="cev-calendar-detail-title">${this._escapeHtml(this._formatSelectedDate(this.selectedDate))}</h3>`;

        if (!events.length) {
            section.innerHTML += `<p class="cev-calendar-empty">${this._escapeHtml(this.options.labels.emptyDay)}</p>`;
            return section;
        }

        events.forEach((event) => {
            const item = document.createElement('button');
            item.type = 'button';
            item.className = 'cev-calendar-detail-item';
            item.dataset.eventId = String(event.id);
            item.innerHTML = `
                <span class="cev-calendar-dot" style="background:${this._escapeHtml(event.color || this._getDefaultEventColor())}"></span>
                <span class="cev-calendar-item-title">${this._escapeHtml(event.title || 'Evento')}</span>
                <span class="cev-calendar-item-meta">${this._escapeHtml(event.time || '')}</span>
            `;
            item.addEventListener('click', () => this._emitEventClick(event));
            section.appendChild(item);
        });

        return section;
    }

    _renderListView() {
        const section = document.createElement('section');
        section.className = 'cev-calendar-list';

        const title = document.createElement('h3');
        title.className = 'cev-calendar-list-title';
        title.textContent = `Eventos de ${this._formatMonthTitle(this.currentDate)}`;
        section.appendChild(title);

        const events = this._getEventsForMonth(this.currentDate.getFullYear(), this.currentDate.getMonth());

        if (!events.length) {
            const empty = document.createElement('p');
            empty.className = 'cev-calendar-empty';
            empty.textContent = this.options.labels.emptyList;
            section.appendChild(empty);
            return section;
        }

        events.forEach((event) => {
            const item = document.createElement('button');
            item.type = 'button';
            item.className = 'cev-calendar-list-item';
            item.innerHTML = `
                <span class="cev-calendar-dot" style="background:${this._escapeHtml(event.color || this._getDefaultEventColor())}"></span>
                <span class="cev-calendar-item-title">${this._escapeHtml(this._formatEventDateLabel(event))} - ${this._escapeHtml(event.title || 'Evento')}</span>
                <span class="cev-calendar-item-meta">${this._escapeHtml(event.time || '')}</span>
            `;
            item.addEventListener('click', () => this._emitEventClick(event));
            section.appendChild(item);
        });

        return section;
    }

    _createButton(content, onClick) {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'cev-calendar-btn';
        button.innerHTML = content;
        button.addEventListener('click', onClick);
        return button;
    }

    _emitEventClick(event) {
        if (typeof this.options.onEventClick === 'function') {
            this.options.onEventClick(event);
        }
    }

    _getOrderedDayNames() {
        const names = this.options.dayNames;
        const start = this.options.firstDayOfWeek;
        return [...names.slice(start), ...names.slice(0, start)];
    }

    _getEventsForDate(dateStr) {
        return this.events
            .filter((event) => event.date === dateStr)
            .sort((a, b) => (a.time || '').localeCompare(b.time || ''));
    }

    _getEventsForMonth(year, month) {
        const prefix = `${year}-${String(month + 1).padStart(2, '0')}-`;
        return this.events
            .filter((event) => typeof event.date === 'string' && event.date.startsWith(prefix))
            .sort((a, b) => `${a.date} ${a.time || ''}`.localeCompare(`${b.date} ${b.time || ''}`));
    }

    prev() {
        this.currentDate.setMonth(this.currentDate.getMonth() - 1);
        this.render();
    }

    next() {
        this.currentDate.setMonth(this.currentDate.getMonth() + 1);
        this.render();
    }

    goToToday() {
        const today = new Date();
        this.currentDate = this._normalizeDate(today);
        this.selectedDate = this._dateStr(today);
        this.render();
    }

    goToDate(date) {
        const normalized = this._normalizeDate(date);
        this.currentDate = normalized;
        this.selectedDate = this._dateStr(normalized);
        this.render();
    }

    changeView(viewName) {
        if (!this.options.views.includes(viewName)) {
            return;
        }
        this.currentView = viewName;
        this.render();
    }

    addEvent(event) {
        const normalized = this._normalizeEvent(event);
        if (!normalized.id) {
            this.eventIdCounter += 1;
            normalized.id = this.eventIdCounter;
        }
        this.events.push(normalized);
        this.render();
        return normalized;
    }

    removeEvent(id) {
        this.events = this.events.filter((event) => String(event.id) !== String(id));
        this.render();
    }

    updateEvent(id, updates = {}) {
        const index = this.events.findIndex((event) => String(event.id) === String(id));
        if (index < 0) {
            return;
        }
        this.events[index] = this._normalizeEvent({ ...this.events[index], ...updates });
        this.render();
    }

    setEvents(events) {
        this.events = this._normalizeEvents(events || []);
        this.render();
    }

    getEvents() {
        return [...this.events];
    }

    getEventsForDate(dateStr) {
        return this._getEventsForDate(dateStr);
    }

    destroy() {
        this.container.innerHTML = '';
        this.container.classList.remove('cev-calendar');
    }

    _normalizeEvents(events) {
        return events.map((event) => this._normalizeEvent(event));
    }

    _normalizeEvent(event = {}) {
        return {
            id: event.id || null,
            date: typeof event.date === 'string' ? event.date : '',
            title: event.title || 'Evento',
            description: event.description || '',
            color: event.color || this._getDefaultEventColor(),
            time: event.time || '',
            type: event.type || 'general',
            openMode: event.openMode || null,
            ...event,
        };
    }

    _normalizeDate(dateInput) {
        if (dateInput instanceof Date) {
            return new Date(dateInput.getFullYear(), dateInput.getMonth(), dateInput.getDate(), 12, 0, 0);
        }

        if (typeof dateInput === 'string') {
            return new Date(`${dateInput}T12:00:00`);
        }

        return new Date();
    }

    _formatMonthTitle(date) {
        const formatter = new Intl.DateTimeFormat(this.options.locale, {
            month: 'long',
            year: 'numeric',
        });
        const text = formatter.format(date);
        return text.charAt(0).toUpperCase() + text.slice(1);
    }

    _formatSelectedDate(dateStr) {
        const date = this._normalizeDate(dateStr);
        const formatter = new Intl.DateTimeFormat(this.options.locale, {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        });
        const text = formatter.format(date);
        return text.charAt(0).toUpperCase() + text.slice(1);
    }

    _formatEventDateLabel(event) {
        if (!event.date) {
            return 'Fecha no definida';
        }
        const date = this._normalizeDate(event.date);
        return new Intl.DateTimeFormat(this.options.locale, {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
        }).format(date);
    }

    _dateStr(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    _getDefaultEventColor() {
        const colors = ['#0d6efd', '#198754', '#dc3545', '#fd7e14', '#6f42c1', '#0dcaf0'];
        return colors[Math.floor(Math.random() * colors.length)];
    }

    _escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = String(text || '');
        return div.innerHTML;
    }
}

window.CevCalendar = CevCalendar;
