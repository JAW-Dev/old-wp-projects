jQuery(document).ready(function(){
    fp_interactive_checklist_setup_print_button();
    fp_interactive_checklist_setup_form_validation();
    fp_interactive_checklist_setup_copy_text_button();
    fp_interactive_checklist_setup_notes_button();
    fp_interactive_checklist_setup_tooltips();
    fp_interactive_checklist_make_radio_toggle_off_able();
});

function fp_interactive_checklist_setup_tooltips(){
    jQuery('.question').each(function(index, element){
        const button = jQuery(element).find('.tooltip-button');
        const tooltip = jQuery(element).find('.tooltip-content');

        button.click(function(){ tooltip.slideToggle(); });
    });
}

function fp_interactive_checklist_setup_notes_button() {
    jQuery('.question').each(function(index, element){
        const noteButton = jQuery(element).find('.notes-button');
        const noteField = jQuery(element).find('.notes');

        noteButton.click(function(){ noteField.slideToggle(); });
    });
}

function fp_interactive_checklist_setup_print_button() {
    jQuery('#interactive-checklist-print-button').click(function(){ window.print(); });
}

function fp_interactive_checklist_setup_form_validation() {
    const unsetRadios = jQuery('#interactive-checklist-form input[type="radio"][value="unset"]');

    jQuery('#interactive-checklist-form #submit-button').click(function(event){
        unsetRadios.prop('checked', false);
    });

    jQuery('#interactive-checklist-form #save-button').click(function(event){
        unsetRadios.each( function(index, element){
            let sibling_is_checked = false;
            
            jQuery(element).siblings().each(function(index, element){
                sibling_is_checked = sibling_is_checked || element.checked;
            });
            
            if (! sibling_is_checked) {
                jQuery(element).prop('checked', true);
            }
        });
    });

    jQuery('#interactive-checklist-form').on("keydown", ":input:not(textarea):not(:submit)", function(event) {
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
}

function fp_interactive_checklist_setup_copy_text_button(){

    const copyToClipboard = function(str) {
        const el = document.createElement('textarea');  // Create a <textarea> element
        el.value = str;                                 // Set its value to the string that you want copied
        el.setAttribute('readonly', '');                // Make it readonly to be tamper-proof
        el.style.position = 'absolute';                 
        el.style.left = '-9999px';                      // Move outside the screen to make it invisible
        document.body.appendChild(el);                  // Append the <textarea> element to the HTML document
        const selected =            
            document.getSelection().rangeCount > 0        // Check if there is any content selected previously
            ? document.getSelection().getRangeAt(0)     // Store selection if found
            : false;                                    // Mark as false to know no selection existed before
        el.select();                                    // Select the <textarea> content
        document.execCommand('copy');                   // Copy - only works as a result of a user action (e.g. click events)
        document.body.removeChild(el);                  // Remove the <textarea> element
        if (selected) {                                 // If a selection existed before copying
            document.getSelection().removeAllRanges();    // Unselect everything on the HTML document
            document.getSelection().addRange(selected);   // Restore the original selection
        }
    };
    
    jQuery('#interactive-checklist-copy-button').click(function() {
        copyToClipboard( jQuery('#interactive-checklist-plain-text').text() );
    });
}

function fp_interactive_checklist_make_radio_toggle_off_able() {
    jQuery('.question-group .question .inputs').each( function(index, element) {
        const inputs = jQuery(element).find('input');

        inputs.filter(':checked').attr('data-already-selected', true);
        
        inputs.change(function(event){
            inputs.attr('data-already-selected', false);
            event.target.dataset.alreadySelected = true;
        });
        
        inputs.click(function(event){
            if ('true' === event.target.dataset.alreadySelected) {
                event.target.checked = false;
                event.target.dataset.alreadySelected = false;
            }
        });
    });
}
