import { Controller } from '@hotwired/stimulus';
import Sortable from 'sortablejs';

export default class extends Controller {
    static targets = ['column', 'toast'];
    static values = { url: String };

    connect() {
        this.sortables = this.columnTargets.map((column) =>
            Sortable.create(column, {
                group: 'tasks',
                animation: 150,
                ghostClass: 'opacity-40',
                chosenClass: 'drag-chosen',
                dragClass: 'drag-card',
                handle: '.task-card',
                onEnd: (event) => this.onEnd(event),
            })
        );
    }

    disconnect() {
        this.sortables.forEach((sortable) => sortable.destroy());
        this.sortables = [];
    }

    onEnd(event) {
        const newStatus = event.to.dataset.status;
        const oldStatus = event.from.dataset.status;

        this.updateCounters();

        if (newStatus === oldStatus) {
            return;
        }

        const badge = event.item.querySelector('[data-status-badge]');
        if (badge) {
            badge.textContent = event.to.dataset.statusLabel;
            badge.className = `px-2 py-1 text-xs rounded-full ${event.to.dataset.statusClass}`;
        }

        this.persist(event.item.dataset.taskId, newStatus);
    }

    updateCounters() {
        this.columnTargets.forEach((column) => {
            const count = column.querySelectorAll('.task-card').length;
            const badge = document.getElementById(`counter-${column.dataset.status}`);

            if (badge) {
                badge.textContent = count;
            }

            let empty = column.querySelector('.empty-label');

            if (count === 0 && !empty) {
                empty = document.createElement('p');
                empty.className = 'empty-label text-sm text-ink-faint text-center py-4';
                empty.textContent = 'Aucune tâche';
                column.appendChild(empty);
            } else if (count > 0 && empty) {
                empty.remove();
            }
        });
    }

    async persist(taskId, status) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

        try {
            const response = await fetch(this.urlValue, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ id: taskId, status }),
            });

            if (!response.ok) {
                throw new Error(`Erreur serveur ${response.status}`);
            }

            this.showToast('Statut mis à jour', 'success');
        } catch (error) {
            console.error(error);
            this.showToast('Échec de la mise à jour', 'error');
        }
    }

    showToast(message, type) {
        const toast = this.toastTarget;
        toast.textContent = message;
        toast.className = `fixed bottom-6 right-6 px-4 py-3 rounded-lg text-sm font-medium text-white shadow-lg transition-all duration-300 z-50 ${
            type === 'success' ? 'bg-success' : 'bg-danger'
        }`;
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';

        clearTimeout(this.toastTimeout);
        this.toastTimeout = setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(1rem)';
        }, 3000);
    }
}
