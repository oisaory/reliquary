import { Controller } from '@hotwired/stimulus';

/**
 * Floating Action Button controller
 *
 * This controller manages the functionality of the FAB that reopens the saint card
 */
export default class extends Controller {
    static targets = ['card', 'fab'];

    connect() {
        // Initialize Bootstrap tooltip safely
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            new bootstrap.Tooltip(this.fabTarget);
        }
        
        // Check if card was previously closed
        const isClosed = localStorage.getItem('saintCardClosed') === 'true';
        if (isClosed && this.hasCardTarget) {
            this.cardTarget.style.display = 'none';
            this.showFAB();
        }
        
        // Add event listeners
        this.addEventListeners();
    }
    
    addEventListeners() {
        // We need to find the close button within the card
        const closeBtn = this.cardTarget.querySelector('[data-action="close-saint-card"]');
        
        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.closeCard());
        }
    }
    
    closeCard() {
        if (this.hasCardTarget) {
            this.cardTarget.style.display = 'none';
            this.showFAB();
            localStorage.setItem('saintCardClosed', 'true');
        }
    }
    
    openCard() {
        if (this.hasCardTarget) {
            this.cardTarget.style.display = 'block';
            this.fabTarget.style.display = 'none';
            localStorage.setItem('saintCardClosed', 'false');
        }
    }
    
    showFAB() {
        if (this.hasFabTarget) {
            this.fabTarget.style.display = 'flex';
        }
    }
}