jQuery(document).ready(function() {
	jQuery('.onlyCEEligible').on('click', function() {
		jQuery('.objNewsTable').toggleClass('eligibleOnly');
		jQuery('.not-ce-eligible').slideToggle("slow");
	});
});
