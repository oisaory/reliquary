import { Controller } from '@hotwired/stimulus';

/**
 * Search Expansion Controller
 * 
 * This controller manages the expandable search bar that reveals search options
 * when clicked. It provides options to search by address or by saint, with
 * keyboard navigation support.
 * 
 * @class SearchExpansionController
 * @extends Controller
 */
export default class extends Controller {
    /** @type {Array<String>} - Stimulus targets */
    static targets = ['container', 'input', 'options', 'icon', 'optionButton'];

    /** @type {Object} - CSS class names used throughout the controller */
    static classes = {
        HIDDEN: 'd-none',
        EXPANDED: 'search-expanded',
        HIGHLIGHTED: 'highlighted',
        SEARCH_ICON: 'fa-search',
        CLOSE_ICON: 'fa-times'
    };

    /** @type {Number} - Index of the currently highlighted option */
    highlightedIndex = 0;

    /** @type {Function} - Bound event handlers to prevent memory leaks */
    #boundHandleClickOutside;
    #boundHandleKeydown;

    /**
     * Initialize the controller when it connects to the DOM
     */
    connect() {
        // Validate required targets
        if (!this.validateTargets()) {
            return;
        }

        // Hide options initially
        this.hideOptions();
        
        // Bind event handlers once to avoid creating new functions
        this.#boundHandleClickOutside = this.handleClickOutside.bind(this);
        this.#boundHandleKeydown = this.handleKeydown.bind(this);
        
        // Add event listeners
        document.addEventListener('click', this.#boundHandleClickOutside);
        this.inputTarget.addEventListener('keydown', this.#boundHandleKeydown);
    }

    /**
     * Clean up event listeners when controller disconnects
     */
    disconnect() {
        document.removeEventListener('click', this.#boundHandleClickOutside);
        
        if (this.hasInputTarget) {
            this.inputTarget.removeEventListener('keydown', this.#boundHandleKeydown);
        }
    }

    // -------------------------------------------------------------------------
    // Event Handlers
    // -------------------------------------------------------------------------
    
    /**
     * Handle keyboard navigation when options are visible
     * 
     * @param {KeyboardEvent} event - The keyboard event
     */
    handleKeydown(event) {
        if (!this.isOptionsVisible()) {
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
                this.selectHighlightedOption();
                break;
            case 'Escape':
                event.preventDefault();
                this.closeOptions();
                break;
        }
    }
    
    /**
     * Handle clicks outside the search container to close options
     * 
     * @param {MouseEvent} event - The click event
     */
    handleClickOutside(event) {
        if (!this.containerTarget.contains(event.target) && this.isOptionsVisible()) {
            this.closeOptions();
        }
    }
    
    /**
     * Handle clicks on the search/close icon
     * 
     * @param {MouseEvent} event - The click event
     */
    handleIconClick(event) {
        event.stopPropagation();
        
        if (this.isOptionsVisible()) {
            this.closeOptions();
        } else {
            this.toggle(event);
        }
    }

    /**
     * Handle option selection
     * 
     * @param {MouseEvent} event - The click event on an option
     */
    selectOption(event) {
        const option = event.currentTarget.dataset.option;
        const placeholder = option === 'address' ? 'Search by address...' : 'Search by saint...';
        
        this.inputTarget.setAttribute('placeholder', placeholder);
        this.inputTarget.dataset.searchType = option;
        
        this.clearHighlights();
        this.closeOptions();
        this.inputTarget.focus();
    }

    // -------------------------------------------------------------------------
    // UI Actions
    // -------------------------------------------------------------------------

    /**
     * Toggle the visibility of search options
     * 
     * @param {Event} event - The triggering event
     */
    toggle(event) {
        event.stopPropagation();
        
        const isOpening = !this.isOptionsVisible();
        
        this.toggleOptionsVisibility();
        this.toggleContainerExpanded();
        this.toggleSearchIcon();

        if (isOpening) {
            this.setupInitialState();
        }
    }
    
    /**
     * Close the options panel and reset UI state
     */
    closeOptions() {
        this.hideOptions();
        this.containerTarget.classList.remove(this.constructor.classes.EXPANDED);
        this.showSearchIcon();
    }

    // -------------------------------------------------------------------------
    // Option Highlighting Methods
    // -------------------------------------------------------------------------
    
    /**
     * Highlight the next option in the list
     */
    highlightNextOption() {
        if (!this.hasOptionButtonTargets || this.optionButtonTargets.length === 0) {
            return;
        }
        
        this.clearHighlights();
        this.highlightedIndex = (this.highlightedIndex + 1) % this.optionButtonTargets.length;
        this.highlightOption(this.highlightedIndex);
    }
    
    /**
     * Highlight the previous option in the list
     */
    highlightPreviousOption() {
        if (!this.hasOptionButtonTargets || this.optionButtonTargets.length === 0) {
            return;
        }
        
        this.clearHighlights();
        this.highlightedIndex = (this.highlightedIndex - 1 + this.optionButtonTargets.length) % this.optionButtonTargets.length;
        this.highlightOption(this.highlightedIndex);
    }
    
    /**
     * Highlight a specific option by index
     * 
     * @param {Number} index - The index of the option to highlight
     */
    highlightOption(index) {
        if (!this.hasOptionButtonTargets || index < 0 || index >= this.optionButtonTargets.length) {
            return;
        }
        
        this.optionButtonTargets[index].classList.add(this.constructor.classes.HIGHLIGHTED);
    }
    
    /**
     * Clear all option highlights
     */
    clearHighlights() {
        if (!this.hasOptionButtonTargets) {
            return;
        }
        
        this.optionButtonTargets.forEach(button => {
            button.classList.remove(this.constructor.classes.HIGHLIGHTED);
        });
    }
    
    /**
     * Select the currently highlighted option
     */
    selectHighlightedOption() {
        if (!this.hasOptionButtonTargets || this.optionButtonTargets.length === 0 || 
            this.highlightedIndex < 0 || this.highlightedIndex >= this.optionButtonTargets.length) {
            return;
        }
        
        this.optionButtonTargets[this.highlightedIndex].click();
    }

    // -------------------------------------------------------------------------
    // Helper Methods
    // -------------------------------------------------------------------------
    
    /**
     * Check if all required targets are available
     * 
     * @returns {Boolean} - True if all required targets exist
     */
    validateTargets() {
        if (!this.hasContainerTarget || !this.hasInputTarget || !this.hasOptionsTarget) {
            console.error('Search expansion controller requires container, input, and options targets');
            return false;
        }
        return true;
    }
    
    /**
     * Check if the options panel is currently visible
     * 
     * @returns {Boolean} - True if options are visible
     */
    isOptionsVisible() {
        return !this.optionsTarget.classList.contains(this.constructor.classes.HIDDEN);
    }
    
    /**
     * Hide the options panel
     */
    hideOptions() {
        this.optionsTarget.classList.add(this.constructor.classes.HIDDEN);
    }
    
    /**
     * Toggle the visibility of the options panel
     */
    toggleOptionsVisibility() {
        this.optionsTarget.classList.toggle(this.constructor.classes.HIDDEN);
    }
    
    /**
     * Toggle the expanded state of the container
     */
    toggleContainerExpanded() {
        this.containerTarget.classList.toggle(this.constructor.classes.EXPANDED);
    }
    
    /**
     * Toggle between search and close icons
     */
    toggleSearchIcon() {
        if (!this.hasIconTarget) {
            return;
        }
        
        this.iconTarget.classList.toggle(this.constructor.classes.SEARCH_ICON);
        this.iconTarget.classList.toggle(this.constructor.classes.CLOSE_ICON);
    }
    
    /**
     * Show the search icon (hide the close icon)
     */
    showSearchIcon() {
        if (!this.hasIconTarget) {
            return;
        }
        
        this.iconTarget.classList.add(this.constructor.classes.SEARCH_ICON);
        this.iconTarget.classList.remove(this.constructor.classes.CLOSE_ICON);
    }
    
    /**
     * Set up the initial state when opening the options panel
     */
    setupInitialState() {
        this.inputTarget.focus();
        this.clearHighlights();
        this.highlightedIndex = 0;
        this.highlightOption(this.highlightedIndex);
    }
}