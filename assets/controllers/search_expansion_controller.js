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
        // If options are hidden and Enter is pressed, submit the form if it's an address search with input
        if (this.optionsTarget.classList.contains('d-none') && event.key === 'Enter') {
            if (this.inputTarget.dataset.searchType === 'address' && this.inputTarget.value.trim() !== '') {
                if (this.hasFormTarget) {
                    event.preventDefault();
                    this.formTarget.submit();
                }
            }
            return;
        }
        
        // If options are hidden, don't process other keys
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

        if (isOpening) {
            this.openOptions();
        } else {
            this.closeOptions();
        }
    }

    openOptions() {
        this.optionsTarget.classList.remove('d-none');
        this.containerTarget.classList.add('search-expanded');

        if (this.hasIconTarget) {
            this.iconTarget.classList.remove('fa-search');
            this.iconTarget.classList.add('fa-times');
        }

        this.inputTarget.focus();
        this.clearHighlights();
        this.highlightedIndex = 0;
        this.highlightOption(this.highlightedIndex);
    }
    
    handleClickOutside(event) {
        if (!this.containerTarget.contains(event.target) && 
            !this.optionsTarget.classList.contains('d-none')) {
            this.closeOptions();
        }
    }
    
    handleIconClick(event) {
        event.stopPropagation();
        event.preventDefault();

        if (!this.optionsTarget.classList.contains('d-none')) {
            this.closeOptions();
        } else {
            this.openOptions(event);
        }
    }

    selectOption(event) {
        const option = event.currentTarget.dataset.option;
        this.inputTarget.setAttribute('placeholder', 
            option === 'address' ? 'Search for location...' : 'Search by saint...');
        this.inputTarget.dataset.searchType = option;
        
        // Update form action based on selected option
        if (this.hasFormTarget) {
            if (option === 'address') {
                this.formTarget.setAttribute('action', '/');
            } else {
                this.formTarget.setAttribute('action', '/saint');
            }
            this.formTarget.submit();
            this.formTarget.dataset.searchType = option;
        }
        
        this.clearHighlights();
        this.closeOptions();
    }
}