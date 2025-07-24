import { Controller } from '@hotwired/stimulus';

/**
 * Search Expansion controller
 *
 * This controller handles the expansion of the search bar to reveal search options
 * when clicked, providing options to search by address or by saint.
 */
export default class extends Controller {
    static targets = ['container', 'input', 'options', 'icon'];

    connect() {
        // Ensure we have the required targets
        if (!this.hasContainerTarget || !this.hasInputTarget || !this.hasOptionsTarget) {
            console.error('Search expansion controller requires container, input, and options targets');
            return;
        }

        // Hide options initially
        this.optionsTarget.classList.add('d-none');
        
        // Add click outside listener to close the expanded search
        document.addEventListener('click', this.handleClickOutside.bind(this));
    }

    disconnect() {
        // Clean up event listener when controller is disconnected
        document.removeEventListener('click', this.handleClickOutside.bind(this));
    }

    // Toggle the expanded search options
    toggle(event) {
        // Prevent the click from propagating to document (which would trigger handleClickOutside)
        event.stopPropagation();
        
        // Toggle the options visibility
        this.optionsTarget.classList.toggle('d-none');
        
        // Toggle the expanded class on the container for styling
        this.containerTarget.classList.toggle('search-expanded');
        
        // Toggle the icon if it exists
        if (this.hasIconTarget) {
            this.iconTarget.classList.toggle('fa-search');
            this.iconTarget.classList.toggle('fa-times');
        }
        
        // Focus the input when expanded
        if (!this.optionsTarget.classList.contains('d-none')) {
            this.inputTarget.focus();
        }
    }
    
    // Handle clicks outside the search container to close it
    handleClickOutside(event) {
        if (!this.containerTarget.contains(event.target) && 
            !this.optionsTarget.classList.contains('d-none')) {
            this.optionsTarget.classList.add('d-none');
            this.containerTarget.classList.remove('search-expanded');
            
            if (this.hasIconTarget) {
                this.iconTarget.classList.add('fa-search');
                this.iconTarget.classList.remove('fa-times');
            }
        }
    }
    
    // Select a search option
    selectOption(event) {
        const option = event.currentTarget.dataset.option;
        this.inputTarget.setAttribute('placeholder', 
            option === 'address' ? 'Search by address...' : 'Search by saint...');
        this.inputTarget.dataset.searchType = option;
        
        // Close the options after selection
        this.optionsTarget.classList.add('d-none');
        
        // Focus the input after selection
        this.inputTarget.focus();
    }
}