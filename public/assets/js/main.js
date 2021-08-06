(function ($) {
	'use strict';

	function imageLoaded() {
    	imagesLoaded++;
    	if (imagesLoaded == totalImages) {
			$grid.masonry();
    	}
	}

	function initMasonry() {
		let grid = $('.grid').masonry({
			isInitLayout: false,
			itemSelector: '.grid-item',
			columnWidth: 120,
			gutter: 12,
			transitionDuration: '1.5s'
		});

		let imagesLoaded = 0;
		let totalImages = $('img').length;

		$('img').each(function(idx, img) {
			$('<img>').on('load', imageLoaded).attr('src', $(img).attr('src'));
		});
	}

	function toggleFavorite(element) {
		let src = $(element).data('src');
		$.post( "api/toggle-favorites", { src: src }).done(function( data ) {
			if (data) {
				$(element).removeClass('chosen');
				if ($(element).parent(".mb-3.favorites")) {
					$(element).parent(".mb-3.favorites").remove();
				}
			} else {
				$(element).addClass('chosen');
			}
		});
	}

	function toggleSelectMode(element) {
		let enabled = $(element).hasClass('btn-info');

		if (enabled) {
			$(element).addClass('btn-secondary').removeClass('btn-info').html("Select Mode: Off");
			$(".favToggler").addClass("d-none");
		} else {
			$(element).addClass('btn-info').removeClass('btn-secondary').html("Select Mode: On");
			$(".favToggler").removeClass("d-none");
		}

		$.post( "api/toggle-favorite-mode", { enabled: enabled });
	}

	function toggleViewMode(element) {
		let grid = $(element).hasClass('viewGrid');

		if (grid) {
			$(element).removeClass('viewGrid').html("View Mode: Column");
		} else {
			$(element).addClass('viewGrid').html("View Mode: Masonry");
			$(".favorites").addClass("grid");
			let grid = $('.grid');
			if ( grid.length > 0 ) {
				initMasonry();
			}
		}
	}

	function clearAllFavorites() {
		$.post( "api/clearall-favorites", { src: 1 }).done(function() {
			location.reload();
		});
	}

	$(document).ready(function() {
		let items = $('.item');
		if ( items.length > 0 ) {
			items.simpleLightbox();
		}

		let grid = $('.grid');
		if ( grid.length > 0 ) {
			initMasonry();
		}

		$('.container').on("click", '.favToggler', function(e){
			toggleFavorite(this);
		});

		$('.container').on("click", '#enableSelect', function(e){
			toggleSelectMode(this);
		});

		$('.container').on("click", '#toggleView', function(e){
			toggleViewMode(this);
		});

		$('.container').on("click", '#clearAll', function(e){
			clearAllFavorites();
		});
	});
})(jQuery);