import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['container', 'input', 'options', 'icon', 'optionButton', 'form'];

    highlightedIndex = 0;

    connect() {
        if (!this.hasContainerTarget || !this.hasInputTarget || !this.hasOptionsTarget) {
            console.error('Search expansion controller requires container, input, and options targets');
            return;
        }

        this.optionsTarget.classList.add('d-none');

        document.addEventListener('click', this.handleClickOutside.bind(this));

        this.inputTarget.addEventListener('keydown', this.handleKeydown.bind(this));
    }

    disconnect() {
        document.removeEventListener('click', this.handleClickOutside.bind(this));
        this.inputTarget.removeEventListener('keydown', this.handleKeydown.bind(this));
    }
    
    handleKeydown(event) {
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
    
    highlightNextOption() {
        this.clearHighlights();
        this.highlightedIndex = (this.highlightedIndex + 1) % this.optionButtonTargets.length;
        this.highlightOption(this.highlightedIndex);
    }
    
    highlightPreviousOption() {
        this.clearHighlights();
        this.highlightedIndex = (this.highlightedIndex - 1 + this.optionButtonTargets.length) % this.optionButtonTargets.length;
        this.highlightOption(this.highlightedIndex);
    }
    
    highlightOption(index) {
        if (index >= 0 && index < this.optionButtonTargets.length) {
            this.optionButtonTargets[index].classList.add('highlighted');
        }
    }
    
    clearHighlights() {
        this.optionButtonTargets.forEach(button => {
            button.classList.remove('highlighted');
        });
    }
    
    closeOptions() {
        this.optionsTarget.classList.add('d-none');
        this.containerTarget.classList.remove('search-expanded');
        
        if (this.hasIconTarget) {
            this.iconTarget.classList.add('fa-search');
            this.iconTarget.classList.remove('fa-times');
        }
    }

    toggle(event) {
        event.stopPropagation();
        const isOpening = this.optionsTarget.classList.contains('d-none');
        this.optionsTarget.classList.toggle('d-none');
        this.containerTarget.classList.toggle('search-expanded');

        if (this.hasIconTarget) {
            this.iconTarget.classList.toggle('fa-search');
            this.iconTarget.classList.toggle('fa-times');
        }

        if (isOpening) {
            this.inputTarget.focus();
            this.clearHighlights();
            this.highlightedIndex = 0;
            this.highlightOption(this.highlightedIndex);
        }
    }
    
    handleClickOutside(event) {
        if (!this.containerTarget.contains(event.target) && 
            !this.optionsTarget.classList.contains('d-none')) {
            this.closeOptions();
        }
    }
    
    handleIconClick(event) {
        event.stopPropagation();

        if (!this.optionsTarget.classList.contains('d-none')) {
            this.closeOptions();
        } else {
            this.toggle(event);
        }
    }

    selectOption(event) {
        const option = event.currentTarget.dataset.option;
        this.inputTarget.setAttribute('placeholder', 
            option === 'address' ? 'Search by address...' : 'Search by saint...');
        this.inputTarget.dataset.searchType = option;
        
        // Update form action based on selected option
        if (this.hasFormTarget) {
            if (option === 'address') {
                // For address search, we would need a different route
                // This is a placeholder - you would need to create this route
                this.formTarget.setAttribute('action', '/relic');
            } else {
                // For saint search, use the route we created
                this.formTarget.setAttribute('action', '/saint');
            }
            this.formTarget.dataset.searchType = option;
            
            // Submit the form immediately after selecting an option
            this.formTarget.submit();
        }
        
        this.clearHighlights();
        this.closeOptions();
    }
}