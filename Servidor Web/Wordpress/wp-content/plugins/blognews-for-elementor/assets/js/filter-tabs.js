window.onload = () => {};

const run = (filter, scope) => {
	const selectedFilter = filter.getAttribute("data-filter");
	const allItems = scope.querySelectorAll(".blogfoel-filter-item");

	allItems.forEach((item) => {
		const itemFilter = item.getAttribute("data-filter");
		const showItem = selectedFilter === "All" || itemFilter === selectedFilter;

		if (showItem) {
			item.style.display = "block";
			item.style.opacity = 0;
			item.style.transition = "opacity 0.4s ease-in-out";

			requestAnimationFrame(() => {
				item.style.opacity = 1;
			});
		} else {
			item.style.transition = "opacity 0.4s ease-in-out";
			item.style.opacity = 0;

			setTimeout(() => {
				item.style.display = "none";
			}, 5); // wait for fade-out
		}
	});
};
const load = ($scope, $) => {
	const wId = $scope.attr("data-id");
	const wrapper = document.querySelector(`.elementor-element-${wId}`);
	const filters = wrapper.querySelectorAll(".blogfoel-filter-tab");

	if (filters.length > 0) {
		run(filters[0], wrapper);
	}

	filters.forEach((filter) => {
		filter.addEventListener("click", function () {
			run(filter, wrapper);
		});
	});

	// Add Active Class on Filter Click
	const selector = $scope.find('.blogfoel-filter-tab');
	jQuery(selector).on('click', function () {
		jQuery(selector).removeClass('active');
		jQuery(this).addClass('active');
	});
};

// Elementor Init Hook

jQuery(window).on("elementor/frontend/init", function () {
	elementorFrontend.hooks.addAction(
		"frontend/element_ready/blognews-filter-tabs-1.default",
		load
	);
});
jQuery(window).on("elementor/frontend/init", function () {
	elementorFrontend.hooks.addAction(
		"frontend/element_ready/blognews-filter-tabs-2.default",
		load
	);
});