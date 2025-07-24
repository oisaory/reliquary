import { Controller } from '@hotwired/stimulus';

/**
 * Search Expansion controller
 *
 * This controller handles the expansion of the search bar to reveal search options
 * when clicked, providing options to search by address or by saint.
 * It also supports keyboard navigation (up/down arrows) and selection (enter).
 */
export default class extends Controller {
    static targets = ['container', 'input', 'options', 'icon', 'optionButton'];
    
    // Track the currently highlighted option index
    highlightedIndex = 0;

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
        
        // Add keyboard event listener for navigation
        this.inputTarget.addEventListener('keydown', this.handleKeydown.bind(this));
    }

    disconnect() {
        // Clean up event listeners when controller is disconnected
        document.removeEventListener('click', this.handleClickOutside.bind(this));
        this.inputTarget.removeEventListener('keydown', this.handleKeydown.bind(this));
    }
    
    // Handle keyboard navigation
    handleKeydown(event) {
        // Only process keyboard navigation when options are visible
        if (this.optionsTarget.classList.contains('d-none')) {
            return;
        }
        
        switch (event.key) {
            case 'ArrowDown':
                event.preventDefault();
                this.highlightNextOption();
                break;
            case 'ArrowUp':
                event.preventDefault();
                this.highlightPreviousOption();
                break;
            case 'Enter':
                event.preventDefault();
                // If options are visible and we have a highlighted option, select it
                if (this.optionButtonTargets.length > 0 && this.highlightedIndex >= 0) {
                    this.optionButtonTargets[this.highlightedIndex].click();
                }
                break;
            case 'Escape':
                event.preventDefault();
                this.closeOptions();
                break;
        }
    }
    
    // Highlight the next option in the list
    highlightNextOption() {
        this.clearHighlights();
        this.highlightedIndex = (this.highlightedIndex + 1) % this.optionButtonTargets.length;
        this.highlightOption(this.highlightedIndex);
    }
    
    // Highlight the previous option in the list
    highlightPreviousOption() {
        this.clearHighlights();
        this.highlightedIndex = (this.highlightedIndex - 1 + this.optionButtonTargets.length) % this.optionButtonTargets.length;
        this.highlightOption(this.highlightedIndex);
    }
    
    // Highlight a specific option by index
    highlightOption(index) {
        if (index >= 0 && index < this.optionButtonTargets.length) {
            this.optionButtonTargets[index].classList.add('highlighted');
        }
    }
    
    // Clear all highlights
    clearHighlights() {
        this.optionButtonTargets.forEach(button => {
            button.classList.remove('highlighted');
        });
    }
    
    // Close the options panel
    closeOptions() {
        this.optionsTarget.classList.add('d-none');
        this.containerTarget.classList.remove('search-expanded');
        
        if (this.hasIconTarget) {
            this.iconTarget.classList.add('fa-search');
            this.iconTarget.classList.remove('fa-times');
        }
    }

    // Toggle the expanded search options
    toggle(event) {
        // Prevent the click from propagating to document (which would trigger handleClickOutside)
        event.stopPropagation();
        
        const isOpening = this.optionsTarget.classList.contains('d-none');
        
        // Toggle the options visibility
        this.optionsTarget.classList.toggle('d-none');
        
        // Toggle the expanded class on the container for styling
        this.containerTarget.classList.toggle('search-expanded');
        
        // Toggle the icon if it exists
        if (this.hasIconTarget) {
            this.iconTarget.classList.toggle('fa-search');
            this.iconTarget.classList.toggle('fa-times');
        }
        
        // When opening the options
        if (isOpening) {
            // Focus the input
            this.inputTarget.focus();
            
            // Clear any existing highlights
            this.clearHighlights();
            
            // Highlight the first option (saint) by default
            this.highlightedIndex = 0;
            this.highlightOption(this.highlightedIndex);
        }
    }
    
    // Handle clicks outside the search container to close it
    handleClickOutside(event) {
        if (!this.containerTarget.contains(event.target) && 
            !this.optionsTarget.classList.contains('d-none')) {
            this.closeOptions();
        }
    }
    
    // Handle clicks on the search icon/X
    handleIconClick(event) {
        // Prevent the click from propagating to document (which would trigger handleClickOutside)
        event.stopPropagation();
        
        // If options are visible (X is showing), close them
        if (!this.optionsTarget.classList.contains('d-none')) {
            this.closeOptions();
        } else {
            // Otherwise toggle to open (same as clicking the input)
            this.toggle(event);
        }
    }
    
    // Select a search option
    selectOption(event) {
        const option = event.currentTarget.dataset.option;
        this.inputTarget.setAttribute('placeholder', 
            option === 'address' ? 'Search by address...' : 'Search by saint...');
        this.inputTarget.dataset.searchType = option;
        
        // Clear highlights
        this.clearHighlights();
        
        // Close the options after selection
        this.closeOptions();
        
        // Focus the input after selection
        this.inputTarget.focus();
    }
}